<?php


namespace App\Listeners;


use App\Src\Users\Infrastructure\Model\UserSyncDiscourseModel;
use Illuminate\Auth\Events\Verified;
use Illuminate\Support\Facades\Log;

class SetUserToSyncOnDiscourse
{
    public function handle(Verified $verified)
    {
        try {
            $user = $verified->user;
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
