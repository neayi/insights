<?php


namespace App\Src\UseCases\Domain;


use App\Events\UserDeleted;
use App\Events\UserLeaveOrganization;
use App\Mail\UserJoinsOrganizationToUser;
use App\Src\UseCases\Domain\Ports\UserRepository;
use App\Src\UseCases\Domain\Users\Identity;
use App\Src\UseCases\Domain\Users\State;
use App\Src\UseCases\Domain\Users\UserDto;
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
    private $providers;

    public function __construct(
        string $id,
        string $email,
        string $firstname,
        string $lastname,
        string $organizationId = null,
        string $pathPicture = null,
        array $roles = [],
        array $providers = []
    )
    {
        $this->id = $id;
        $this->email = $email;
        $this->lastname = $lastname;
        $this->firstname = $firstname;
        $this->organizationId = $organizationId;
        $this->pathPicture = $pathPicture;
        $this->roles = $roles;
        $this->providers = $providers;
    }

    public function email():string
    {
        return $this->email;
    }

    public function id():string
    {
        return $this->id;
    }

    public function fullname():string
    {
        return ucfirst($this->firstname).' '.ucfirst($this->lastname);
    }

    public function organizationId():?string
    {
        return $this->organizationId;
    }

    public function provider(string $provider, string $providerId):bool
    {
        if(isset($this->providers[$provider]) && $this->providers[$provider] == $providerId){
            return true;
        }
        return false;
    }

    public function belongsTo(string $organisationId):bool
    {
        return $this->organizationId === $organisationId;
    }

    public function create(string $passwordHashed = null, Picture $picture = null)
    {
        if(isset($picture)) {
            $picture->resize('app/public/users/' . $this->id);
            $this->pathPicture = $picture->relativePath();
        }
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

    public function leaveOrganization()
    {
        $this->organizationId = null;
        app(UserRepository::class)->update($this);
        event(new UserLeaveOrganization());
    }

    public function isAdmin():bool
    {
        return in_array('admin', $this->roles);
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

    public function delete()
    {
        app(UserRepository::class)->delete($this->id);
        event(new UserDeleted($this->id, $this->organizationId));
    }

    public function toDto():UserDto
    {
        $identity = new Identity($this->id, $this->email, $this->firstname, $this->lastname, $this->pathPicture);
        $state = new State($this->organizationId, null, true);
        return new UserDto($identity, $state);
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
            'roles' => $this->roles,
            'providers' => $this->providers
        ];
    }
}
