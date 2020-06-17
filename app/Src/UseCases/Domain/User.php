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
    private $pathPicture;
    private $roles;

    public function __construct(string $id, string $email, string $firstname, string $lastname, string $organizationId = null, string $pathPicture = null, array $roles = [])
    {
        $this->id = $id;
        $this->email = $email;
        $this->lastname = $lastname;
        $this->firstname = $firstname;
        $this->organizationId = $organizationId;
        $this->pathPicture = $pathPicture;
        $this->roles = $roles;
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

    public function belongsTo(string $organisationId):bool
    {
        return $this->organizationId === $organisationId;
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

    public function grantAsAdmin()
    {
        $this->roles = array_merge($this->roles, ['admin']);
        app(UserRepository::class)->update($this);
    }

    public function revokeAsAdmin()
    {
        $this->roles = [];
        app(UserRepository::class)->update($this);
    }

    public function update(string $email, string $firstname, string $lastname, string $pathPicture, string $ext = 'jpg')
    {
        $this->email = $email;
        $this->firstname = $firstname;
        $this->lastname = $lastname;
        if($pathPicture !== "") {
            $picture = new Picture($pathPicture);
            $picture->resize('app/public/users/' . $this->id . '.' . $ext);
            $this->pathPicture = 'app/public/users/' . $this->id . '.' . $ext;
        }
        app(UserRepository::class)->update($this);
    }

    public function toArray()
    {
        $urlPicture = $this->pathPicture != "" ? asset('storage/'.str_replace('app/public/', '', $this->pathPicture)) : null;
        return [
            'uuid' => $this->id,
            'email' => $this->email,
            'firstname' => $this->firstname,
            'lastname' => $this->lastname,
            'organization_id' => $this->organizationId,
            'path_picture' => $this->pathPicture,
            'url_picture' => $urlPicture,
            'roles' => $this->roles
        ];
    }
}
