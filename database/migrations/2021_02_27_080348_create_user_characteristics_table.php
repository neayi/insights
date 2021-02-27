<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserCharacteristicsTable extends Migration
{
    public function up()
    {
        Schema::create('user_characteristics', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id');
            $table->integer('characteristic_id');
            $table->json('value');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('user_characteristics');
    }
}
