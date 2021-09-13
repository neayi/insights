<?php

use Illuminate\Database\Migrations\Migration;

class SetSocialNetworksUsersEmailVerified extends Migration
{
    public function up()
    {
        \App\User::where('email_verified_at', null)
            ->chunkById(50, function($users){
                foreach($users as $user){
                    if($user->providers !== null && !empty($user->providers)){
                        $user->markEmailAsVerified();
                    }
                }
            });
    }

    public function down()
    {
        // nothing to do
    }
}
