<?php


namespace App\Console\Commands;


use App\Src\UseCases\Infra\Sql\Model\UserSyncDiscourseModel;
use App\User;
use GuzzleHttp\Client;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

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
        /*$sync = new UserSyncDiscourseModel();
        $sync->user_id = 1;
        $sync->uuid = 1;
        $sync->sync = false;
        $sync->save();
        return;*/

        $hostname = config('services.discourse.api.url');
        $httpClient = new Client(['base_uri' => $hostname]);

        UserSyncDiscourseModel::query()->where('sync', false)->chunkById(50, function ($items) use ($httpClient) {
            foreach($items as $userSync) {
                $user = $userSync->user;
                try {
                    $this->createUserOnDiscourse($httpClient, $user);


                    $userSync->sync = true;
                    $userSync->sync_at = (new \DateTime())->format('Y-m-d H:i:s');
                    $userSync->save();
                    /*if(!$result){
                        $message = 'No result from Api. Discourse sync failed for user : '.$user->uuid;
                        $this->error($message);
                        Log::emergency($message);
                    }*/
                }catch (\Throwable $e){
                    $message = 'Discourse sync failed for user : '.$user->uuid. ' '.$e->getMessage();
                    $this->error($message);
                    Log::emergency($message);
                    report($e);
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
                'name' => $user->lastname,
                'username' => $user->fullname(),
                'password' => uniqid().uniqid(),
                'email' => $user->email,
            ]
        ]);
        dd($result);
    }

    private function uploadAvatar(Client $httpClient, User $user)
    {
        $apiKey = config('services.discourse.api.key');

        $result = $httpClient->post('uploads.json', [
            'headers' => [
                'Api-Key' => $apiKey,
                'Api-Username' => 'system',
                'Content-Type' => 'application/json'
            ],
            'json' => [
                'type' => 'avatar',
                'user_id' => $user->id,
                'synchronous' => true,
                'file' => $user->getAvatarUrlAttribute(),
            ]
        ]);


        $uri = 'u/'.$user->fullname().'/preferences/avatar/pick.json';
        $httpClient->put($uri, [
            'headers' => [
                'Api-Key' => $apiKey,
                'Api-Username' => 'system',
                'Content-Type' => 'application/json'
            ],
            'json' => [
                'upload_id' => $result['upload_id'],
                'type' => 'uploaded',
            ]
        ]);
    }
}
