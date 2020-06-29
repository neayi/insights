<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;

class CreateArbo extends Migration
{
    public function up()
    {
        Storage::makeDirectory('public/organizations');
        Storage::makeDirectory('public/users');
        Artisan::call('storage:link');
    }

    public function down()
    {

    }
}
