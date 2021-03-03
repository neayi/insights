<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;

class CreateCharacteristicsFolder extends Migration
{
    public function up()
    {
        Storage::makeDirectory('public/characteristics');
    }

    public function down()
    {

    }
}
