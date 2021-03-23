<?php


namespace App\Src\UseCases\Domain\Shared\Gateway;

use App\Src\UseCases\Domain\Auth\SocialiteUser;

interface SocialiteGateway
{
    public function user(string $provider): SocialiteUser;
}
