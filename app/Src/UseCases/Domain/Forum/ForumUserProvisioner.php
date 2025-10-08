<?php

declare(strict_types=1);

namespace App\Src\UseCases\Domain\Forum;

use App\LocalesConfig;
use App\Src\ForumApiClient;
use App\Src\UseCases\Infra\Sql\Model\DiscourseProfileModel;
use App\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use \Throwable;


class ForumUserProvisioner
{
    /** array<string, {client: ForumApiClient}> */
    private array $syncerConfig = [];

    private function initSyncerConfig()
    {
        foreach (LocalesConfig::all() as $wikiLocale) {
            $this->syncerConfig[$wikiLocale->code]['client'] = new ForumApiClient($wikiLocale->forum_api_url, $wikiLocale->forum_api_key);
        }
    }

    public function getUserDiscourseUsername(int $userId, string $locale): ?string
    {
        if (empty($this->syncerConfig)) {
            $this->initSyncerConfig();
        }

        try {
            $discourseUsername = DB::table('discourse_profiles')->where('user_id', $userId)->where('locale', $locale)->value('username');
        } catch (Throwable $e) {
            Log::error(
                sprintf('Error fetching Discourse username for user ID %s and locale %s: %s', $userId, $locale, $e->getMessage())
            );

            return null;
        }

        // Creation of the Discourse profile if it does not exist yet
        if (!$discourseUsername) {
            $user = User::where('id', $userId)->first();

            if (!$user) {
                Log::info(sprintf('User with ID %s not found, skipping', $userId));

                return null;
            }

            $discourseUsername = $this->createUserOnDiscourse($user, $locale);

            Log::notice(
                sprintf(
                    'Discourse profile created for user with ID %s and locale %s (Discourse username: %s)',
                    $userId,
                    $locale,
                    $discourseUsername)
                );
        }

        return $discourseUsername;
    }

    private function createUserOnDiscourse(User $user, string $locale, int $increment = 0): string
    {
        Log::info(sprintf('Creating Discourse user for insight account "%s" and locale "%s"', $user->uuid, $locale));

        if (empty($user->email_verified_at)) {
            throw new \Exception("Email not verified", 54);
        }

        $username = $this->formatUsername($user, $increment);

        $result = $this->syncerConfig[$locale]['client']->createUser($username, $user);
        if($result['success'] === false){
            if (!empty($result['errors']['email'])) {
                $discourseUser = $this->findUserByIdOrEmail($user, $locale);
                if ($discourseUser) {
                    // The user already exists, we can update it:
                    $this->saveDiscourseProfile($user, $locale, $discourseUser['user_id'], $discourseUser['username']);

                    return $discourseUser['username'];
                } else {
                    $increment++;
                    return $this->createUserOnDiscourse($user, $locale, $increment);
                }
            }

            if (!empty($result['errors']['username'][0]) &&
                strpos($result['errors']['username'][0], 'unique') !== false) {
                $increment++;
                return $this->createUserOnDiscourse($user, $locale, $increment);
            }

            throw new \Exception($result['message']);
        }

        $this->saveDiscourseProfile($user, $locale, $result['user_id'], $username);

        return $username;
    }

    private function formatUsername(User $user, int $increment): string
    {
        $username = trim(substr((string)Str::of($user->fullname)->slug('.'), 0, 20), '.');
        if (empty($username)) {
            throw new \Exception("Empty username", 55);
        }

        return !empty($increment) ? $username . $increment : $username;
    }

    /**
     * Save or update the discourse profile for the given user and locale
     *
     * @return string Discourse username
     */
    private function saveDiscourseProfile(User $user, string $locale, int $discourseId, string $discourseUsername): string
    {
        $discourseProfile = DiscourseProfileModel::where('user_id', $user->id)->where('locale', $locale)->first();

        if (null === $discourseProfile) {
            $discourseProfile = new DiscourseProfileModel();
            $discourseProfile->user_id = $user->id;
            $discourseProfile->locale = $locale;

            Log::info('Discourse profile created on discourse with ext_id : '.$discourseId);
        } else {
            Log::info('Discourse profile was already created on discourse with ext_id : '.$discourseProfile->ext_id);
        }

        $discourseProfile->ext_id = $discourseId;
        $discourseProfile->username = $discourseUsername;
        $discourseProfile->synced_at = date('Y-m-d H:i:s');

        $discourseProfile->save();

        return $discourseUsername;
    }

    /**
     * Try to find a discourse user using the Insights ID or the email
     */
    private function findUserByIdOrEmail(User $user, string $locale)
    {
        try {
            $result = $this->syncerConfig[$locale]['client']->getUserByInsightId($user->id);
            if (!empty($result[0])) {
                return $result[0];
            }
        }
        catch (\Throwable $e){
            if ($e->getCode() === 404) {
                // do nothing
            } else {
                $message = 'Discourse sync failed for user : ' . $user->uuid . ' [' . $e->getCode() . '] ' . $e->getMessage();
                Log::error($message);
                \Sentry\captureException($e);
            }
        }

        // Remove the part in the email after the + sign up to the @
        $userEmail = preg_replace('/\+.*?@/', '@', $user->email);
        try {
            $result = $this->syncerConfig[$locale]['client']->getUserByEmail($userEmail);
            if (!empty($result[0])) {
                return $result[0];
            }
        }
        catch (\Throwable $e){
            if ($e->getCode() === 404) {
                // do nothing
            } else {
                $message = 'Discourse sync failed for user : ' . $user->uuid . ' [' . $e->getCode() . '] ' . $e->getMessage();
                Log::error($message);
                \Sentry\captureException($e);
            }
        }

        // Replace @ with %@ in order to get also people with non normalized emails
        // See https://meta.discourse.org/t/enabling-e-mail-normalization-by-default/338641?tl=fr
        $userEmail = str_replace('@', '+%@', $userEmail);
        try {
            $result = $this->syncerConfig[$locale]['client']->getUserByEmail($userEmail);
            if (!empty($result[0])) {
                return $result[0];
            }
        }
        catch (\Throwable $e){
            if ($e->getCode() === 404) {
                // do nothing
            } else {
                $message = 'Discourse sync failed for user : ' . $user->uuid . ' [' . $e->getCode() . '] ' . $e->getMessage();
                Log::error($message);
                \Sentry\captureException($e);
            }
        }

        return false;
    }
}
