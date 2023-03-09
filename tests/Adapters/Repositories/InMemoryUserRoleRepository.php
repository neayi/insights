<?php


namespace Tests\Adapters\Repositories;

use App\Src\Users\Dto\Role;
use App\Src\Users\UserRoleRepository;
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
