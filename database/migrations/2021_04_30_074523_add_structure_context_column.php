<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddStructureContextColumn extends Migration
{
    public function up()
    {
        Schema::table('contexts', function (Blueprint $table){
            $table->string('structure', 255)->nullable()->default(null);
            $table->integer('structure_id');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::table('contexts', function (Blueprint $table){
            $table->dropColumn('structure');
            $table->dropColumn('structure_id');
            $table->dropTimestamps();
        });
    }
}
