<?php


namespace App\Src\UseCases\Domain\Users;


class State
{
    private $organizationId;
    private $state;
    private $lastLoginAt;
    private $roles;

    public function __construct(?string $organizationId, ?string $lastLoginAt, bool $state = false, array $roles = [])
    {
        $this->organizationId = $organizationId;
        $this->state = $state;
        $this->lastLoginAt = $lastLoginAt;
        $this->roles = $roles;
    }

    public function toArray()
    {
        return [
            'organization_id' => $this->organizationId,
            'roles' => $this->roles,
            'state' => $this->state,
            'last_login_at' => $this->lastLoginAt
        ];
    }
}
