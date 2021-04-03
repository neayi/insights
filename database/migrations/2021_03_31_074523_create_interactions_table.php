<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use \Illuminate\Support\Facades\DB;

class CreateInteractionsTable extends Migration
{
    public function up()
    {
        Schema::create('interactions', function (Blueprint $table){
            $table->id();
            $table->integer('page_id');
            $table->integer('user_id')->nullable();
            $table->string('cookie_user_session_id')->nullable();
            $table->boolean('applause')->default(false);
            $table->boolean('follow')->default(false);
            $table->boolean('done')->default(false);
            $table->timestamp('start_done_at')->nullable();
            $table->json('value')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::drop('interactions');
    }
}
