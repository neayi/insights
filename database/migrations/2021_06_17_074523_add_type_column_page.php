<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTypeColumnPage extends Migration
{
    public function up()
    {
        Schema::table('pages', function (Blueprint $table){
            $table->string('type')->nullable();
            $table->string('icon')->nullable();
        });
    }

    public function down()
    {
        Schema::table('pages', function (Blueprint $table){
            $table->dropColumn('type');
            $table->dropColumn('icon');
        });
    }
}
