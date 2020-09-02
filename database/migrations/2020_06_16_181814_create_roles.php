<?php

use Illuminate\Database\Migrations\Migration;
use Spatie\Permission\Models\Role;

class CreateRoles extends Migration
{
    public function up()
    {
        Role::create(['name' => 'admin']);
    }

    public function down()
    {
        Role::where('name',  'admin')->delete();
    }
}
