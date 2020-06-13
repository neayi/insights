<?php


namespace App\Src\UseCases\Infra\Gateway\Auth;


use App\Src\UseCases\Domain\User;

interface AuthGateway
{
    public function current():? User;
    public function log(User $u);
}
