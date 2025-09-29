<?php

declare(strict_types=1);

namespace App\Src\UseCases\Domain\Forum;

use App\LocalesConfig;
use App\Src\ForumApiClient;
use App\User;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

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

    public function createOrUpdateUserOnDiscourse(User $user)
    {
        if (empty($this->syncerConfig)) {
            $this->initSyncerConfig();
        }

        $existingDiscourseUser = $this->findUserByIdOrEmail($user);

        // TODO
    }

    private function createUserOnDiscourse(User $user, string $locale, int $increment = 0)
    {
        Log::info(sprintf('Creating Discourse user for insight account "%s" and locale "%s"', $user->uuid, $user->locale));

        if (empty($user->email_verified_at)) {
            throw new \Exception("Email not verified", 54);
        }

        $username = $this->formatUsername($user, $increment);

        $result = $this->syncerConfig[$locale]['client']->createUser($username, $user);
        if($result['success'] === false){
            if (!empty($result['errors']['email'])) {
                $discourseUser = $this->findUserByIdOrEmail($user);
                if ($discourseUser) {
                    // The user already exists, we can update it:
                    return $this->updateUsernameFromDiscourse($discourseUser, $user);
                }
                else {
                    $increment++;
                    return $this->createUserOnDiscourse($user, $locale, $increment);
                }
            }

            if (!empty($result['errors']['username'][0]) &&
                strpos($result['errors']['username'][0], 'unique') !== false) {
                $increment++;
                return $this->createUserOnDiscourse($user, $increment);
            }

            throw new \Exception($result['message']);
        }
        $user->discourse_id = $result['user_id'];
        $user->discourse_username = $username;
        $user->save();
        Log::info('User created on discourse with id : '.$user->discourse_id);

        return $result['user_id'];
    }

    /**
     * @param User $user
     * @param int $increment
     * @return string
     * @throws \Exception
     */
    public function formatUsername(User $user, int $increment): string
    {
        $username = trim(substr((string)Str::of($user->fullname)->slug('.'), 0, 20), '.');
        if (empty($username)) {
            throw new \Exception("Empty username", 55);
        }

        return !empty($increment) ? $username . $increment : $username;
    }

    /**
     * Let's assume the user already exists on Discourse, lets ask for the username and discourse id
     * If found, we store it in our DB
     *
     * Return the user_id on success, throw an exception otherwise
     */
    private function updateUsernameFromDiscourse(Array $discourseUser, User $user)
    {
        $user->discourse_id = $discourseUser['id'];
        $user->discourse_username = $discourseUser['username'];
        $user->save();

        Log::info('User was already created on discourse with id : '.$user->discourse_id);

        return $discourseUser['id'];
    }

    /**
     * Try to find a discourse user using the Insights ID or the email
     */
    private function findUserByIdOrEmail(User $user) {
        try {
            $result = $this->forumApiClient->getUserByInsightId($user->id);
            if (!empty($result[0])) {
                return $result[0];
            }
        }
        catch (\Throwable $e){
            if ($e->getCode() === 404) {
                // do nothing
            } else {
                $message = 'Discourse sync failed for user : ' . $user->uuid . ' [' . $e->getCode() . '] ' . $e->getMessage();
                $this->error($message);
                \Sentry\captureException($e);
            }
        }

        // Remove the part in the email after the + sign up to the @
        $userEmail = preg_replace('/\+.*?@/', '@', $user->email);
        try {
            $result = $this->forumApiClient->getUserByEmail($userEmail);
            if (!empty($result[0])) {
                return $result[0];
            }
        }
        catch (\Throwable $e){
            if ($e->getCode() === 404) {
                // do nothing
            } else {
                $message = 'Discourse sync failed for user : ' . $user->uuid . ' [' . $e->getCode() . '] ' . $e->getMessage();
                $this->error($message);
                \Sentry\captureException($e);
            }
        }

        // Replace @ with %@ in order to get also people with non normalized emails
        // See https://meta.discourse.org/t/enabling-e-mail-normalization-by-default/338641?tl=fr
        $userEmail = str_replace('@', '+%@', $userEmail);
        try {
            $result = $this->forumApiClient->getUserByEmail($userEmail);
            if (!empty($result[0])) {
                return $result[0];
            }
        }
        catch (\Throwable $e){
            if ($e->getCode() === 404) {
                // do nothing
            } else {
                $message = 'Discourse sync failed for user : ' . $user->uuid . ' [' . $e->getCode() . '] ' . $e->getMessage();
                $this->error($message);
                \Sentry\captureException($e);
            }
        }

        return false;
    }
}
