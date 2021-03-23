<?php


namespace Tests\Adapters\Gateway;


use App\Src\UseCases\Domain\Shared\Gateway\AuthGateway;
use App\Src\UseCases\Domain\User;

class InMemoryAuthGateway implements AuthGateway
{
    private $currentUser = null;

    public function log(User $u)
    {
        $this->currentUser = $u;
    }

    public function current(): ?User
    {
        return $this->currentUser;
    }
}
