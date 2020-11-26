<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddUserContextTable extends Migration
{
    public function up()
    {
        Schema::create('contexts', function (Blueprint $table){
            $table->id();
            $table->uuid('uuid');
            $table->string('postal_code', 10)->nullable()->default(null);
            $table->json('farmings')->nullable()->default(null);
        });

        Schema::table('users', function (Blueprint $table){
            $table->integer('context_id')->nullable()->default(null);
        });
    }

    public function down()
    {
        Schema::drop('contexts');
        Schema::table('users', function (Blueprint $table){
            $table->dropColumn('context_id');
        });
    }
}
