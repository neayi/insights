<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCoordinatesDepartmentNumberColumnsContext extends Migration
{
    public function up()
    {
        Schema::table('contexts', function (Blueprint $table){
            $table->json('coordinates')->nullable();
            $table->string('department_number')->nullable();
        });
    }

    public function down()
    {
        Schema::table('contexts', function (Blueprint $table){
            $table->dropColumn('coordinates');
            $table->dropColumn('department_number');
        });
    }
}
