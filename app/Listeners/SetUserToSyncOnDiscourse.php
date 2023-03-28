<?php


namespace App\Listeners;


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

            $user->sync_at_discourse = null;
            $user->save();
        }catch (\Throwable $e){
            Log::emergency('Error when sync asking for user : '.$user->id);
            \Sentry\captureException($e);
        }
    }
}
