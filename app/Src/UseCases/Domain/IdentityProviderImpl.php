<?php


namespace App\Src\UseCases\Domain;


use App\Src\UseCases\Domain\Ports\IdentityProvider;
use Ramsey\Uuid\Uuid;

class IdentityProviderImpl implements IdentityProvider
{
    private $id;

    public function id():string
    {
        return $this->id ?? Uuid::uuid4();
    }

    public function setId(string $id)
    {
        $this->id = $id;
    }
}
