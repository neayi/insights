<?php

use App\Src\UseCases\Infra\Sql\Model\UserSyncDiscourseModel;
use Illuminate\Database\Migrations\Migration;

class AskSyncUsersDiscourse extends Migration
{
    public function up()
    {
        \App\User::where('email_verified_at', null)
            ->chunkById(50, function($users){
                foreach($users as $user){
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
        // nothing to do
    }
}
