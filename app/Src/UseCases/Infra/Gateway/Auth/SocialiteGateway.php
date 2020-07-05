<?php


namespace App\Src\UseCases\Infra\Gateway\Auth;


use App\Src\UseCases\Domain\Auth\SocialiteUser;

interface SocialiteGateway
{
    public function user(string $provider): SocialiteUser;
}
