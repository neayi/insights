<?php


namespace App\Src\Shared\Provider;


use App\Src\Shared\IdentityProvider;
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
