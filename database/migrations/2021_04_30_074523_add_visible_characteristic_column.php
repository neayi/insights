<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddVisibleCharacteristicColumn extends Migration
{
    public function up()
    {
        Schema::table('characteristics', function (Blueprint $table){
            $table->boolean('visible')->default(true);
        });
    }

    public function down()
    {
        Schema::table('characteristics', function (Blueprint $table){
            $table->dropColumn('visible');
        });
    }
}
