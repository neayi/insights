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
                        $id = $this->createUserOnDiscourse($httpClient, $user);
                    }else{
                        $id = $this->updateUserOnDiscourse($httpClient, $user);
                    }
                    $userSync->sync = true;
                    $userSync->sync_at = (new \DateTime())->format('Y-m-d H:i:s');
                    $userSync->save();
                    $this->uploadAvatar($httpClient, $user, $id);
                }catch (\Throwable $e){

                    if ($e->getCode() == 429)
                    {
                        // Too many requests - aborting for this time. Will need to relaunch the task in order
                        // to process the rest of the entities
                        $this->error('Too many requests - please relaunch the command in a few minutes');
                        return false; // stop chunkById from continuing
                    }

                    $message = 'Discourse sync failed for user : '.$user->uuid. ' ['. $e->getCode() . '] ' . $e->getMessage();
                    $this->error($message);
                    \Sentry\captureException($e);
                }
            }
        });
    }

    private function createUserOnDiscourse(Client $httpClient, User $user)
    {
        $apiKey = config('services.discourse.api.key');
        $result = $httpClient->post('users.json', [
            'headers' => [
                'Api-Key' => $apiKey,
                'Api-Username' => 'system',
                'Content-Type' => 'application/json'
            ],
            'json' => [
                'username' => $this->username = trim(substr(Str::of($user->fullname())->slug('.'), 0, 20), '.'),
                'password' => uniqid().uniqid(),
                'email' => $user->email,
            ]
        ]);
        $result = json_decode($result->getBody()->getContents(), true);
        if($result['success'] === false){
            throw new \Exception($result['message']);
        }
        $user->discourse_id = $result['user_id'];
        $user->discourse_username = $this->username;
        $user->save();
        $this->info('User created on discourse with id : '.$user->discourse_id);
        return $result['user_id'];
    }

    private function updateUserOnDiscourse(Client $httpClient, User $user)
    {
        $apiKey = config('services.discourse.api.key');
        $result = $httpClient->get('/admin/users/'.$user->discourse_id.'.json', [
            'headers' => [
                'Api-Key' => $apiKey,
                'Api-Username' => 'system',
                'Content-Type' => 'application/json'
            ]
        ]);

        $result = json_decode($result->getBody()->getContents(), true);
        $this->username = $result['username'];
        try {
            $result = $httpClient->put('u/' . $this->username . '/preferences/email.json', [
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


        $this->info('User updated on discourse with id : '.$user->discourse_id);
        return $user->discourse_id;
    }

    private function uploadAvatar(Client $httpClient, User $user, $id)
    {
        $avatarFilename = storage_path($user->path_picture);
        if (empty($user->path_picture) || !file_exists($avatarFilename))
            return;

        $apiKey = config('services.discourse.api.key');
        $result = $httpClient->post('uploads.json', [
            'headers' => [
                'Api-Key' => $apiKey,
                'Api-Username' => 'system',
            ],
            'multipart' => [
                [
                    'name'     => 'type',
                    'contents' => 'avatar',
                ],
                [
                    'name'     => 'user_id',
                    'contents' => $id,
                ],
                [
                    'name'     => 'synchronous',
                    'contents' => true,
                ],
                [
                    'name'     => 'file',
                    'contents' => fopen($avatarFilename, 'r'),
                ],
            ]
        ]);

        $result = json_decode($result->getBody()->getContents(), true);

        $uploadId = $result['id'];

        $uri = 'u/'.$this->username.'/preferences/avatar/pick.json';
        $httpClient->put($uri, [
            'headers' => [
                'Api-Key' => $apiKey,
                'Api-Username' => 'system',
                'Content-Type' => 'application/json'
            ],
            'json' => [
                'upload_id' => $uploadId,
                'type' => 'uploaded',
            ]
        ]);
    }
}
