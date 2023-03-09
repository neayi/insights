<?php


namespace App\Src\Users;


class UserDto
{
    private $identity;
    private $state;

    public function __construct(Identity $identity, State $state)
    {
        $this->identity = $identity;
        $this->state = $state;
    }

    public function toArray()
    {
        return array_merge(
            $this->identity->toArray(),
            $this->state->toArray()
        );
    }
}
