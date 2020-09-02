<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Spatie\Permission\Models\Permission;

class CreatePermissions extends Migration
{
    private function addPermissionsTo(array $permissions, array $roles)
    {
        foreach ($permissions as $permission) {
            try {
                Permission::create(['name' => $permission]);
            }catch (\Throwable $e){
            }
        }

        foreach ($roles as $role){
            $role = \Spatie\Permission\Models\Role::where('name', $role)->first();
            foreach ($permissions as $permission) {
                $role->givePermissionTo($permission);
            }
        }
    }

    private function deletePermissions(array $permissions)
    {
        foreach ($permissions as $permission) {
            try {
                Permission::where('name', $permission)->delete();
            }catch (\Throwable $e){
                // nothing to do
            }
        }
    }

    private $permissionsAdmin = [
        'api.organization.show',
        'api.organization.edit',
        'api.organization.user.invite',
        'api.organization.user.grant',
        'api.organization.user.revoke',
        'api.organization.user.edit',
        'api.organization.user.remove',

        'menu.organization.list',
        'menu.organization.add',
    ];

    private $permissionsSuperAdmin = [
        'menu.organization.dropdown',
        'menu.organization.list',
        'menu.organization.add',
    ];

    public function up()
    {
        app()->make(\Spatie\Permission\PermissionRegistrar::class)->forgetCachedPermissions();
        $this->addPermissionsTo($this->permissionsAdmin, ['admin']);
        $this->addPermissionsTo($this->permissionsSuperAdmin, []);
    }

    public function down()
    {
        $this->deletePermissions($this->permissionsAdmin);
        $this->deletePermissions($this->permissionsSuperAdmin);
    }
}
