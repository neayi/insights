<?php


namespace App\Src\UseCases\Domain\Ports;


use App\Src\UseCases\Domain\Users\Dto\Role;
use Illuminate\Support\Collection;

interface UserRoleRepository
{
    public function add(Role  $role);
    public function all():Collection;
}
