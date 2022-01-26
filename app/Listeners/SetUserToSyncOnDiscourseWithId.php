<?php


namespace App\Listeners;


use App\Events\UserUpdated;
use App\Src\UseCases\Infra\Sql\Model\UserSyncDiscourseModel;
use App\User;
use Illuminate\Support\Facades\Log;

class SetUserToSyncOnDiscourseWithId
{
    public function handle(UserUpdated $userUpdated)
    {
        try {
            $user = User::query()->where('uuid', $userUpdated->userId)->first();
            if(empty($user->firstname) || empty($user->lastname)){
                return;
            }
            $sync = UserSyncDiscourseModel::where('user_id', $user->id)->first();
            if(!isset($sync)) {
                $sync = new UserSyncDiscourseModel();
            }
            $sync->user_id = $user->id;
            $sync->uuid = $user->uuid;
            $sync->sync = false;
            $sync->save();
        }catch (\Throwable $e){
            Log::emergency('Error when sync asking for user : '.$user->id);
            \Sentry\captureException($e);
        }
    }
}
