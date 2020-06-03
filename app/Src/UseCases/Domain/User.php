<?php


namespace App\Src\UseCases\Domain;


use App\Mail\UserJoinsOrganizationToUser;
use App\Src\UseCases\Domain\Ports\UserRepository;
use Illuminate\Support\Facades\Mail;

class User
{
    private $id;
    private $email;
    private $lastname;
    private $firstname;
    private $organizationId;

    public function __construct(string $id, string $email, string $firstname, string $lastname, string $organizationId = null)
    {
        $this->id = $id;
        $this->email = $email;
        $this->lastname = $lastname;
        $this->firstname = $firstname;
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

    public function create(string $passwordHashed)
    {
        app(UserRepository::class)->add($this, $passwordHashed);
    }

    public function joinsOrganization(string $organizationId)
    {
        $this->organizationId = $organizationId;
        app(UserRepository::class)->update($this);
        Mail::to($this->email())->send(new UserJoinsOrganizationToUser());
    }

    public function toArray()
    {
        return [
            'uuid' => $this->id,
            'email' => $this->email,
            'firstname' => $this->firstname,
            'lastname' => $this->lastname,
            'organization_id' => $this->organizationId
        ];
    }
}
