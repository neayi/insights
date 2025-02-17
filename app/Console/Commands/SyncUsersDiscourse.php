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
            $this->forumApiClient = $clients[$user->wiki];
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
                return $this->updateUsernameFromDiscourse($user);
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
    private function updateUsernameFromDiscourse(User $user)
    {
        try {
            $result = $this->forumApiClient->getUserByInsightId($user->id);
        } catch (\Throwable $th) {
            if ($th->getCode() == 404) {
                return $this->updateUsernameFromDiscourseWithEmail($user);
            }

            throw $th;
        }

        if(empty($result['user'])){
            throw new \Exception('Duplicate email not corresponding to existing user');
        }
        $user->discourse_id = $result['user']['id'];
        $user->discourse_username = $result['user']['username'];
        $user->save();

        $this->info('User was already created on discourse with id : '.$user->discourse_id);

        return $result['user']['id'];
    }

    /**
     * Let's assume the user already exists on Discourse, lets ask for the username and discourse id
     * If found, we store it in our DB
     *
     * Not as robust than updateUsernameFromDiscourse but works pretty well anyhow
     *
     * Return the user_id on success, throw an exception otherwise
     */
    private function updateUsernameFromDiscourseWithEmail(User $user)
    {
        $result = $this->forumApiClient->getUserByEmail($user->email);

        if(empty($result[0]['email']) || strtolower($result[0]['email']) != strtolower($user->email)){
            throw new \Exception('Duplicate email not corresponding to existing user');
        }

        $user->discourse_id = $result[0]['id'];
        $user->discourse_username = $result[0]['username'];
        $user->save();

        $this->info('User was already created on discourse with id : '.$user->discourse_id);

        return $result[0]['id'];
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
