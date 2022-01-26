<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDiscourseIdUser extends Migration
{
    public function up()
    {
        Schema::table('users', function (Blueprint $table){
            $table->string('discourse_id')->nullable();
        });
    }

    public function down()
    {
        Schema::table('users', function (Blueprint $table){
            $table->dropColumn('discourse_id');
        });
    }
}
