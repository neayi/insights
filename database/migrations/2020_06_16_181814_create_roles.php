<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Spatie\Permission\Models\Role;

class CreateRoles extends Migration
{
    public function up()
    {
        $role = Role::create(['name' => 'admin']);
    }

    public function down()
    {
        Role::where('name',  'admin')->delete();
    }
}
