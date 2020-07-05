<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;

class AddUserProviderColumn extends Migration
{
    public function up()
    {
        Schema::table('users', function (Blueprint $table){
            $table->json('providers')->nullable()->default(null);
            $table->string('password')->nullable()->change();
        });
    }

    public function down()
    {
        Schema::table('users', function (Blueprint $table){
            $table->dropColumn('providers');
        });
    }
}
