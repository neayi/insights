<?php


namespace App\Src\UseCases\Domain\Ports;

interface IdentityProvider
{
    public function id():string;
    public function setId(string $id);
}
