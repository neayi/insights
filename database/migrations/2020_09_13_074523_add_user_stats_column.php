<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;

class AddUserStatsColumn extends Migration
{
    public function up()
    {
        Schema::table('users', function (Blueprint $table){
            $table->json('wiki_stats')->nullable()->default(null);
        });
    }

    public function down()
    {
        Schema::table('users', function (Blueprint $table){
            $table->dropColumn('wiki_stats');
        });
    }
}
