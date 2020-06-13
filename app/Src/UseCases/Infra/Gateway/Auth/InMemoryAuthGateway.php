<?php


namespace App\Src\UseCases\Infra\Gateway\Auth;


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
