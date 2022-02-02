<?php

use App\Src\UseCases\Infra\Sql\Model\UserSyncDiscourseModel;
use Illuminate\Database\Migrations\Migration;

class AskSyncUsersDiscourse extends Migration
{
    public function up()
    {
        \App\User::whereNotNull('email_verified_at')
            ->chunkById(50, function($users){
                foreach($users as $user){
                    if(empty($user->firstname) || empty($user->lastname)){
                        continue;
                    }
                    $sync = new UserSyncDiscourseModel();
                    $sync->user_id = $user->id;
                    $sync->uuid = $user->uuid;
                    $sync->sync = false;
                    $sync->save();
                }
            });
    }

    public function down()
    {
        \Illuminate\Support\Facades\DB::table('users_sync_discourse')->delete();
    }
}
