<?php


namespace App\Src\Shared\Gateway;

use App\Src\Auth\SocialiteUser;

interface SocialiteGateway
{
    public function user(string $provider): SocialiteUser;
}
