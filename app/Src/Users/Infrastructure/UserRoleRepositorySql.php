<?php


namespace App\Src\Users\Infrastructure;


use App\Src\Users\Dto\Role;
use App\Src\Users\Dto\WikiUserRole;
use App\Src\Users\UserRoleRepository;
use Illuminate\Support\Collection;

class UserRoleRepositorySql implements UserRoleRepository
{
    public function add(Role $role)
    {

    }

    public function all():Collection
    {
        return collect([
            new WikiUserRole('farmer'),
            new WikiUserRole('student'),
            new WikiUserRole('advisor'),
        ]);
    }
}
