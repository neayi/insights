<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersSyncDiscourseTable extends Migration
{
    public function up()
    {
        Schema::create('users_sync_discourse', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id')->unique()->index();
            $table->uuid('uuid')->unique();
            $table->boolean('sync')->default(false);
            $table->dateTime('sync_at')->nullable()->default(null);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('users_sync_discourse');
    }
}
