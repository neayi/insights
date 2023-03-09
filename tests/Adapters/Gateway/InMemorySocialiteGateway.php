<?php


namespace Tests\Adapters\Gateway;


use App\Src\Shared\Gateway\SocialiteGateway;
use App\Src\UseCases\Domain\Auth\SocialiteUser;

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
