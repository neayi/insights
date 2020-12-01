<?php


namespace App\Src\UseCases\Infra\Sql;


use App\Src\UseCases\Domain\Ports\UserRoleRepository;
use App\Src\UseCases\Domain\Users\Dto\Role;
use App\Src\UseCases\Domain\Users\Dto\WikiUserRole;
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
