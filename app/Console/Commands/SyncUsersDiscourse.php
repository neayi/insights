<?php


namespace App\Console\Commands;


use App\Src\UseCases\Infra\Sql\Model\UserSyncDiscourseModel;
use App\User;
use GuzzleHttp\Client;
use Illuminate\Console\Command;
use Illuminate\Support\Str;

use function Psy\debug;

class SyncUsersDiscourse extends Command
{
    protected $signature = 'users:sync-on-discourse';

    protected $description = 'Sync the users. Add users to discourse if they do not exist';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $hostname = config('services.discourse.api.url');
        $httpClient = new Client(['base_uri' => $hostname]);

        UserSyncDiscourseModel::query()
            ->where('sync', false)
            ->chunkById(50, function ($items) use ($httpClient) {
            foreach($items as $userSync) {
                $user = $userSync->user;
                try {
                    if(!isset($user->discourse_id)) {
                        $this->createUserOnDiscourse($httpClient, $user);
                    }else{
                        $this->updateUserEmailOnDiscourse($httpClient, $user);
                    }
                    $this->updateUserBioOnDiscourse($httpClient, $user);
                    $userSync->sync = true;
                    $userSync->sync_at = (new \DateTime())->format('Y-m-d H:i:s');
                    $userSync->save();
                }catch (\Throwable $e){
                    if ($e->getCode() === 429) {
                        // Too many requests - just sleeping
                        $this->error('Too many requests - restarting in a minute....');
                        sleep(60);
                    }else {

                        $message = 'Discourse sync failed for user : ' . $user->uuid . ' [' . $e->getCode() . '] ' . $e->getMessage();
                        $this->error($message);
                        \Sentry\captureException($e);
                    }
                }
            }
        });
    }

    private function createUserOnDiscourse(Client $httpClient, User $user)
    {
        $this->username = trim(substr(Str::of($user->fullname())->slug('.'), 0, 20), '.');

        if (empty($user->email_verified_at))
            throw new \Exception("Email not verified", 54);

        if (empty($this->username))
            throw new \Exception("Empty username", 55);

        $apiKey = config('services.discourse.api.key');
        $result = $httpClient->post('users.json', [
            'headers' => [
                'Api-Key' => $apiKey,
                'Api-Username' => 'system',
                'Content-Type' => 'application/json'
            ],
            'json' => [
                'username' => $this->username,
                'password' => uniqid().uniqid(),
                'email' => $user->email,
            ]
        ]);

        $result = json_decode($result->getBody()->getContents(), true);
        if($result['success'] === false){
            if (!empty($result['errors']['email'])) {
                return $this->updateUsernameFromDiscourse($httpClient, $user);
            }

            throw new \Exception($result['message']);
        }
        $user->discourse_id = $result['user_id'];
        $user->discourse_username = $this->username;
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
    private function updateUsernameFromDiscourse(Client $httpClient, User $user)
    {
        $apiKey = config('services.discourse.api.key');
        $result = $httpClient->get('u/by-external/' . $user->id . '.json', [
            'headers' => [
                'Api-Key' => $apiKey,
                'Api-Username' => 'system',
                'Content-Type' => 'application/json'
            ]
        ]);

        $result = json_decode($result->getBody()->getContents(), true);
        if(empty($result['user'])){
            throw new \Exception('Dupplicate email not corresponding to existing user');
        }

        $user->discourse_id = $result['user']['id'];
        $user->discourse_username = $result['user']['username'];
        $user->save();

        $this->info('User was already created on discourse with id : '.$user->discourse_id);

        return $result['user']['id'];
    }

    private function updateUserEmailOnDiscourse(Client $httpClient, User $user)
    {
        $apiKey = config('services.discourse.api.key');
        try {
            $result = $httpClient->put('u/' . $user->discourse_username . '/preferences/email.json', [
                'headers' => [
                    'Api-Key' => $apiKey,
                    'Api-Username' => 'system',
                    'Content-Type' => 'application/json'
                ],
                'json' => [
                    'email' => $user->email,
                ]
            ]);
            $result = json_decode($result->getBody()->getContents(), true);
            if($result['success'] === false){
                throw new \Exception($result['message']);
            }
        }catch (\Throwable $e){
            // pas besoin d'update l'email
        }

        $this->info('User email updated on discourse with id : '.$user->discourse_username);
        return $user->discourse_id;
    }

    private function updateUserBioOnDiscourse(Client $httpClient, User $user)
    {
        $apiKey = config('services.discourse.api.key');
        try {
            $result = $httpClient->put('u/' . $user->discourse_username . '.json', [
                'headers' => [
                    'Api-Key' => $apiKey,
                    'Api-Username' => 'system',
                    'Content-Type' => 'application/json'
                ],
                'json' => [
                    'bio_raw' => $user->getBioAttribute(),
//                    'location' => $user->location
                ]
            ]);
            $result = json_decode($result->getBody()->getContents(), true);
            if($result['success'] === false){
                throw new \Exception($result['message']);
            }
        }catch (\Throwable $e){
            $this->error('error updating bio : '.$user->discourse_username);
        }

        $this->info('Updating bio with id : '.$user->discourse_username);
        return $user->discourse_id;
    }
}
