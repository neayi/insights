<?php


namespace App\Src\UseCases\Domain\Shared\Gateway;


use App\Src\UseCases\Domain\User;

interface AuthGateway
{
    public function current():? User;
    public function log(User $u);
}
