<?php


namespace App\Console\Commands;


use App\Src\UseCases\Infra\Sql\Model\UserSyncDiscourseModel;
use App\User;
use GuzzleHttp\Client;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;


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
            //->where('sync', false)
            ->chunkById(50, function ($items) use ($httpClient) {
            foreach($items as $userSync) {
                $user = $userSync->user;
                try {
                    $id = $this->createUserOnDiscourse($httpClient, $user);
                    $userSync->sync = true;
                    $userSync->sync_at = (new \DateTime())->format('Y-m-d H:i:s');
                    $userSync->save();
                    $this->uploadAvatar($httpClient, $user, $id);
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
                'username' => $this->user = substr(Str::of($user->fullname())->slug('-'), 0, 20),
                'password' => uniqid().uniqid(),
                'email' => $user->email,
            ]
        ]);
        $result = json_decode($result->getBody()->getContents(), true);
        if($result['success'] === false){
            throw new \Exception($result['message']);
        }
        return $result['user_id'];
    }

    private function uploadAvatar(Client $httpClient, User $user, $id)
    {
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
                    'contents' => fopen(storage_path($user->path_picture), 'r'),
                ],
            ]
        ]);

        $result = json_decode($result->getBody()->getContents(), true);

        $uploadId = $result['id'];
        $uri = 'u/'.$this->user.'/preferences/avatar/pick.json';
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
