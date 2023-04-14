<?php

use App\Src\UseCases\Infra\Sql\Model\UserSyncDiscourseModel;
use App\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSyncAtDiscourseDatetimeColumn extends Migration
{
    public function up()
    {
        Schema::table('users', function (Blueprint $table){
            $table->dateTime('sync_at_discourse')->nullable();
        });

        $this->migrateSyncAtDiscourse();
    }

    public function down()
    {
        Schema::table('users', function (Blueprint $table){
            $table->dropColumn('sync_at_discourse');
        });
    }

    public function migrateSyncAtDiscourse()
    {
        $usersSyncDiscourse = UserSyncDiscourseModel::query()
            ->whereNotNull('sync_at')
            ->get();
        foreach($usersSyncDiscourse as $userSyncDiscourse) {
            $user = User::query()->where('uuid', $userSyncDiscourse->uuid)->first();
            if(!isset($user)) { continue; }
            $user->sync_at_discourse = $userSyncDiscourse->sync_at;
            $user->save();
        }
    }
}
