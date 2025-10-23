<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\LocalesConfig;
use App\Src\ForumApiClient;
use App\User;
use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Log;

class SyncUsersDiscourse extends Command
{
    protected $signature = 'users:sync-on-discourse';

    protected $description = 'Sync the users. Add users to discourse if they do not exist';

    /**
     * @var array<string, ForumApiClient>
     */
    private array $forumApiClients;

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        Log::info('Starting Discourse Sync command');

        foreach (LocalesConfig::all() as $wiki) {
            $this->forumApiClients[$wiki->code] = new ForumApiClient($wiki->forum_api_url, $wiki->forum_api_key);
        }

        User::query()
            ->with('discourseProfiles')
            ->where('firstname', '<>', '')
            ->where('lastname', '<>', '')
            ->whereHas('discourseProfiles', function (Builder $query) {
                $query
                    ->whereNull('synced_at')
                    ->orWhere('synced_at', '<', now()->subDays(30)->toDateTimeString())
                ;
            })
            ->whereNotNull('email_verified_at')
            ->chunkById(50, function ($items) {
                foreach($items as $user) {
                    $this->processOneUser($user);
                }
            });

        Log::info('Ending Discourse Sync command - completed');
    }

    private function processOneUser(User $user)
    {
        Log::info('Discourse Syncing user : '.$user->uuid);

        foreach ($user->discourseProfiles()->get() as $discourseProfile) {
            try {
                $this->updateUserEmailOnDiscourse($discourseProfile->locale, $discourseProfile->username, $user->email);
                $this->updateUserDetailsOnDiscourse($discourseProfile->locale, $discourseProfile->username, $user);
                $discourseProfile->synced_at = (new \DateTime())->format('Y-m-d H:i:s');

                $discourseProfile->save();
            } catch (\Throwable $e){
                if ($e->getCode() === 429) {
                    // Too many requests - just sleeping
                    $this->error('Too many requests - restarting in a minute....');
                    sleep(60);
                } else if ($e->getCode() === 404) {
                    $this->error('User not found on Discourse: ' . $discourseProfile->username);
                } else {
                    $message = 'Discourse sync failed for user : ' . $user->uuid . ' [' . $e->getCode() . '] ' . $e->getMessage();
                    $this->error($message);
                    echo $e->getTraceAsString().PHP_EOL;
                    \Sentry\captureException($e);
                }
            }
        }
    }

    private function updateUserEmailOnDiscourse(string $locale, string $discourseUsername, string $userEmail)
    {
        try {
            $result = $this->forumApiClients[$locale]->updateEmail($discourseUsername, $userEmail);

            if($result['success'] === false) {
                $this->error('Not Updated email : '.$result['message']);
                throw new \Exception($result['message']);
            }

            $this->info('Updated email with id : '.$discourseUsername);
        } catch (\RuntimeException $e) {
         //   $this->info('Updating user email is temporary disabled');
        }
    }

    private function updateUserDetailsOnDiscourse(string $locale, string $discourseUsername, User $user)
    {
        $bioParts = [];
        $bioParts[] = $user->getBioAttribute();
        $bioParts[] = "\n\n[voir plus](".$user->profileUrl().")";
        $newBio = trim(implode("\n", array_filter($bioParts)));

        $result = $this->forumApiClients[$locale]->updateUser($discourseUsername, $user, $newBio);

        if($result['success'] === false){
            $this->error('Not Updated bio : '.$result['message']);
            throw new \Exception($result['message']);
        }

        $this->info('Updated bio with id : '.$discourseUsername);
    }
}
