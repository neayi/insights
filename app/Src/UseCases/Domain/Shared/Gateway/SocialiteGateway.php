<?php


namespace App\Src\UseCases\Domain\Shared\Gateway;


use App\Src\Insights\Auth\Domain\SocialiteUser;

interface SocialiteGateway
{
    public function user(string $provider): SocialiteUser;
}
