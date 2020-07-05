<?php


namespace App\Src\UseCases\Infra\Gateway\InMemory;


use App\Src\UseCases\Domain\Auth\SocialiteUser;
use App\Src\UseCases\Infra\Gateway\Auth\SocialiteGateway;

class InMemorySocialiteGateway implements SocialiteGateway
{
    private $users = [];

    public function user(string $provider): SocialiteUser
    {
        return $this->users[$provider];
    }

    public function add(SocialiteUser $u, string $provider)
    {
        $this->users[$provider] = $u;
    }

}
