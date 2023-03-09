<?php


namespace App\Src\Users;


use App\Src\Users\Dto\Role;
use Illuminate\Support\Collection;

interface UserRoleRepository
{
    public function add(Role  $role);
    public function all():Collection;
}
