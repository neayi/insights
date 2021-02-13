<?php


namespace Tests\Adapters\Repositories;

use App\Src\UseCases\Domain\Ports\UserRoleRepository;
use App\Src\UseCases\Domain\Users\Dto\Role;
use Illuminate\Support\Collection;

class InMemoryUserRoleRepository implements UserRoleRepository
{
    private $roles;

    public function add(Role $role)
    {
        $this->roles[] = $role;
    }

    public function all(): Collection
    {
        return collect($this->roles);
    }

}
