<?php

namespace App\Src\UseCases\Domain;

use App\Src\UseCases\Domain\Ports\UserRepository;
use App\Src\UseCases\Domain\Shared\Model\Picture;
use App\Src\UseCases\Domain\Users\Identity;
use App\Src\UseCases\Domain\Users\UserDto;

class User
{
    private $id;
    private $email;
    private $lastname;
    private $firstname;
    private $pathPicture;
    private $roles;
    private $providers;
    private $discourse_id;
    private $discourse_username;
    private $wiki;

    public function __construct(
        string $id,
        string $email,
        string $firstname,
        string $lastname,
        string $pathPicture = null,
        array $roles = [],
        array $providers = [],
        ?string $discourse_id = '',
        ?string $discourse_username = '',
        string $wiki = 'fr',
    )
    {
        $this->id = $id;
        $this->email = $email;
        $this->lastname = $lastname;
        $this->firstname = $firstname;
        $this->pathPicture = $pathPicture;
        $this->roles = $roles;
        $this->providers = $providers;
        $this->discourse_id = $discourse_id;
        $this->discourse_username = $discourse_username;
        $this->wiki = $wiki;
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

    public function wiki(): string
    {
        return $this->wiki;
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
    }

    public function toDto():UserDto
    {
        $identity = new Identity($this->id, $this->email, $this->firstname, $this->lastname, $this->pathPicture);

        return new UserDto($identity);
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
            'path_picture' => $this->pathPicture,
            'url_picture' => $urlPicture,
            'roles' => $this->roles,
            'providers' => $this->providers
        ];
    }
}
