<?php


namespace App\Src\Users\Domain;


use App\Events\UserDeleted;
use App\Src\Shared\Model\Picture;

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
    private $discourse_id;
    private $discourse_username;

    public function __construct(
        string $id,
        string $email,
        string $firstname,
        string $lastname,
        string $organizationId = null,
        string $pathPicture = null,
        array $roles = [],
        array $providers = [],
        string $discourse_id = '',
        string $discourse_username = ''
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
        $this->discourse_id = $discourse_id;
        $this->discourse_username = $discourse_username;
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
        return $this->firstname.' '.$this->lastname;
    }

    public function provider(string $provider, string $providerId):bool
    {
        if(isset($this->providers[$provider]) && $this->providers[$provider] == $providerId){
            return true;
        }
        return false;
    }

    public function discourse_username():string
    {
        return $this->discourse_username;
    }

    public function addProvider(string $provider, string $providerId)
    {
        $this->providers[$provider] = $providerId;
        app(UserRepository::class)->updateProviders($this);
    }

    public function create(string $passwordHashed = null, Picture $picture = null)
    {
        if(isset($picture)) {
            $picture->resize('app/public/users/' . $this->id);
            $this->pathPicture = $picture->relativePath();
        }
        app(UserRepository::class)->add($this, $passwordHashed);
    }

    public function isAdmin():bool
    {
        return in_array('admin', $this->roles);
    }

    public function update(string $email, string $firstname, string $lastname, string $pathPicture = "", string $ext = 'jpg')
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

    public function updateAvatar(string $pathPicture, string $ext = 'jpg')
    {
        if($pathPicture !== "") {
            $picture = new Picture($pathPicture);
            $picture->resize('app/public/users/' . $this->id . '.' . $ext);
            $this->pathPicture = 'app/public/users/' . $this->id . '.' . $ext;
        }
        app(UserRepository::class)->update($this);
        return $this->pathPicture;
    }

    public function delete()
    {
        app(UserRepository::class)->delete($this->id);
        event(new UserDeleted($this->id, $this->organizationId));
    }

    public function addRole(string $role)
    {
        $this->roles[] = $role;
        app(UserRepository::class)->update($this);
    }

    public function updateRole(string $role)
    {
        $this->roles = [$role];
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
            'roles' => $this->roles,
            'providers' => $this->providers
        ];
    }
}
