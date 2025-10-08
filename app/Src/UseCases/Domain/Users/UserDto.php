<?php

namespace App\Src\UseCases\Domain\Users;

class UserDto
{
    private $identity;

    public function __construct(Identity $identity)
    {
        $this->identity = $identity;
    }

    public function toArray()
    {
        return array_merge(
            $this->identity->toArray(),
            [
                'roles' => [],
            ]
        );
    }
}
