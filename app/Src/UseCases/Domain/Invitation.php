<?php


namespace App\Src\UseCases\Domain;


use App\Src\Utils\Hash\HashGen;

class Invitation
{
    private $organizationId;
    private $email;
    private $firstname;
    private $lastname;
    private $token;
    private $hash;

    public function __construct(string $organizationId, string $email, ?string $firstname = null, ?string $lastname = null)
    {
        $this->organizationId = $organizationId;
        $this->email = $email;
        $this->firstname = $firstname;
        $this->lastname = $lastname;
        $this->token = base64_encode($organizationId.'|*|'.$email.'|*|'.$firstname.'|*|'.$lastname);
        $this->hash = app(HashGen::class)->hash($this->token);
    }

    public function hash():string
    {
        return $this->hash;
    }

    public function data():array
    {
        return [
            $this->organizationId,
            $this->email,
            $this->firstname,
            $this->lastname,
        ];
    }
}
