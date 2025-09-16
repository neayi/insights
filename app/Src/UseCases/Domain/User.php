<?php

namespace App\Src\UseCases\Domain;

use App\Src\UseCases\Domain\Ports\UserRepository;
use App\Src\UseCases\Domain\Shared\Model\Picture;
use App\Src\UseCases\Domain\Users\Identity;
use App\Src\UseCases\Domain\Users\UserDto;

class User
{
    public function __construct (
        private string $id,
        private string $email,
        private string $firstname,
        private string $lastname,
        private ?string $pathPicture = null,
        private array $roles = [],
        private array $providers = [],
        // private ?string $discourse_id = null,
        // private ?string $discourse_username = null,
        private string $default_locale = 'fr',
    )
    {
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

    public function defaultLocale(): string
    {
        return $this->default_locale;
    }

    public function provider(string $provider, string $providerId):bool
    {
        if(isset($this->providers[$provider]) && $this->providers[$provider] == $providerId){
            return true;
        }
        return false;
    }

    // public function discourse_username():string
    // {
    //     return $this->discourse_username;
    // }

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
