<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddUTHSAUContextColumn extends Migration
{
    public function up()
    {
        Schema::table('contexts', function (Blueprint $table){
            $table->float('uth')->nullable()->default(null);
            $table->float('sau')->nullable()->default(null);
        });
    }

    public function down()
    {
        Schema::table('contexts', function (Blueprint $table){
            $table->dropColumn('uth');
            $table->dropColumn('sau');
        });
    }
}
