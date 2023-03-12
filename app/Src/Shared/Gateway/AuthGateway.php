<?php


namespace App\Src\Shared\Gateway;


use App\Src\Users\Domain\User;

interface AuthGateway
{
    public function current():? User;
    public function log(User $u);
    public function wikiSessionId():?string;
}
