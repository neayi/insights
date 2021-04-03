<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use \Illuminate\Support\Facades\DB;

class CreatePageTable extends Migration
{
    public function up()
    {
        Schema::create('pages', function (Blueprint $table){
            $table->id();
            $table->integer('page_id')->unique();
            $table->text('title')->nullable();
            $table->boolean('dry')->default(false);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::drop('pages');
    }
}
