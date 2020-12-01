<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;

class AddUserRoles extends Migration
{
    public function up()
    {
        \Spatie\Permission\Models\Role::create(['name' => 'farmer']);
        \Spatie\Permission\Models\Role::create(['name' => 'student']);
        \Spatie\Permission\Models\Role::create(['name' => 'advisor']);
        \Spatie\Permission\Models\Role::create(['name' => 'searcher']);
        \Spatie\Permission\Models\Role::create(['name' => 'others']);
        \Spatie\Permission\Models\Role::create(['name' => 'agro-supplier']);
    }

    public function down()
    {
        \Spatie\Permission\Models\Role::where('name', 'farmer')->delete();
        \Spatie\Permission\Models\Role::where('name', 'student')->delete();
        \Spatie\Permission\Models\Role::where('name', 'advisor')->delete();
        \Spatie\Permission\Models\Role::where('name', 'searcher')->delete();
        \Spatie\Permission\Models\Role::where('name', 'others')->delete();
        \Spatie\Permission\Models\Role::where('name', 'agro-supplier')->delete();
    }
}
