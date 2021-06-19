<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSectorContextColumn extends Migration
{
    public function up()
    {
        Schema::table('contexts', function (Blueprint $table){
            $table->string('sector', 255)->nullable()->default(null);
            $table->text('description')->nullable()->default(null);
        });
    }

    public function down()
    {
        Schema::table('contexts', function (Blueprint $table){
            $table->dropColumn('sector');
            $table->dropColumn('description');
        });
    }
}
