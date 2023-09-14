<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('contexts', function (Blueprint $table){
            $table->string('country', 255)->nullable()->default(null);
            $table->json('geo')->nullable()->default(null);
        });
    }

    public function down()
    {
        Schema::table('contexts', function (Blueprint $table){
            $table->dropColumn('country');
            $table->dropColumn('geo');
        });
    }
};
