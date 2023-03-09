<?php


namespace App\Src\Auth;


class SocialiteUser
{
    private $providerId;
    private $email;
    private $firstname;
    private $lastname;
    private $pictureUrl;

    public function __construct(string $providerId, ?string $email, string $firstname, string $lastname, string $pictureUrl = null)
    {
        $this->providerId = $providerId;
        $this->email = $email;
        $this->firstname = $firstname;
        $this->lastname = $lastname;
        $this->pictureUrl = $pictureUrl;
    }


    public function providerId(): string
    {
        return $this->providerId;
    }

    public function email():? string
    {
        return $this->email;
    }

    public function firstname(): string
    {
        return $this->firstname;
    }

    public function lastname(): string
    {
        return $this->lastname;
    }

    public function pictureUrl(): ?string
    {
        return $this->pictureUrl;
    }

}
