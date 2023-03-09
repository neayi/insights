<?php


namespace App\Src\Shared;

interface IdentityProvider
{
    public function id():string;
    public function setId(string $id);
}
