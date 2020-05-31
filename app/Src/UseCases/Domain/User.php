<?php


namespace App\Src\UseCases\Domain;


class User
{
    private $id;
    private $email;
    private $organizationId;

    public function __construct(string $id, string $email, string $organizationId = null)
    {
        $this->id = $id;
        $this->email = $email;
        $this->organizationId = $organizationId;
    }

    public function email():string
    {
        return $this->email;
    }

    public function id():string
    {
        return $this->id;
    }

    public function organizationId():?string
    {
        return $this->organizationId;
    }

    public function toArray()
    {
        return [
            'uuid' => $this->id,
            'email' => $this->email,
            'organization_id' => $this->organizationId
        ];
    }
}
