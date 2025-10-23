<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\LocalesConfig;
use App\Src\ForumApiClient;
use App\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class SyncUsersDiscourse extends Command
{
    protected $signature = 'users:sync-on-discourse';

    protected $description = 'Sync the users. Add users to discourse if they do not exist';

    private ForumApiClient $forumApiClient;

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        Log::info('Starting Discourse Sync command');

        $clients = [];
        foreach (LocalesConfig::all() as $wiki) {
            $clients[$wiki->code] = new ForumApiClient($wiki->forum_api_url, $wiki->forum_api_key);
        }

        User::query()
            ->where('firstname', '<>', '')
            ->where('lastname', '<>', '')
            ->whereNotNull('email_verified_at')
            ->whereNull('sync_at_discourse')
            ->chunkById(50, function ($items) use ($clients) {
                foreach($items as $user) {
                    $this->processOneUser($clients, $user);
                }
            });

        Log::info('Ending Discourse Sync command - completed');
    }

    private function processOneUser(array $clients, User $user)
    {
        try {
            $this->forumApiClient = $clients['fr']; // Only create users on the French forum

            Log::info('Discourse Syncing user : '.$user->uuid);
            if (!isset($user->discourse_id)) {
                $this->createUserOnDiscourse($user);
            } else {
                $this->updateUserEmailOnDiscourse($user);
            }
            $this->updateUserDetailsOnDiscourse($user);
            $user->sync_at_discourse = (new \DateTime())->format('Y-m-d H:i:s');
            $user->save();
        } catch (\Throwable $e){
            if ($e->getCode() === 429) {
                // Too many requests - just sleeping
                $this->error('Too many requests - restarting in a minute....');
                sleep(60);
            } else {
                $message = 'Discourse sync failed for user : ' . $user->uuid . ' [' . $e->getCode() . '] ' . $e->getMessage();
                $this->error($message);
                \Sentry\captureException($e);
            }
        }
    }

    private function createUserOnDiscourse(User $user, int $increment = 0)
    {
        if (empty($user->email_verified_at)) {
            throw new \Exception("Email not verified", 54);
        }

        $username = $this->formatUsername($user, $increment);

        $result = $this->forumApiClient->createUser($username, $user);
        if($result['success'] === false){
            if (!empty($result['errors']['email'])) {
                $discourseUser = $this->findUserByIdOrEmail($user);
                if ($discourseUser) {
                    // The user already exists, we can update it:
                    return $this->updateUsernameFromDiscourse($discourseUser, $user);
                }
                else {
                    $increment++;
                    return $this->createUserOnDiscourse($user, $increment);
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
        $this->info('User created on discourse with id : '.$user->discourse_id);

        return $result['user_id'];
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

        $this->info('User was already created on discourse with id : '.$user->discourse_id);

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

    private function updateUserEmailOnDiscourse(User $user)
    {
        try {
            $this->forumApiClient->updateEmail($user->discourse_username, $user->email);
        }catch (\Throwable $e){
            $this->error('User email not updated on discourse with id : '.$user->discourse_username);
        }

        $this->info('User email updated on discourse with id : '.$user->discourse_username);

        return $user->discourse_id;
    }

    private function updateUserDetailsOnDiscourse(User $user)
    {
        $bioParts = [];
        $bioParts[] = $user->getBioAttribute();
        $bioParts[] = "\n\n[voir plus](".$user->profileUrl().")";
        $newBio = trim(implode("\n", array_filter($bioParts)));

        $result = $this->forumApiClient->updateUser($user, $newBio);

        if($result['success'] === false){
            $this->error('Not Updating bio : '.$result['message']);
            throw new \Exception($result['message']);
        }

        $this->info('Updating bio with id : '.$user->discourse_username);

        return $user->discourse_id;
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
}
