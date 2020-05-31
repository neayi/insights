<?php


namespace App\Src\UseCases\Infra\InMemory;


use App\Src\UseCases\Domain\Ports\UserRepository;
use App\Src\UseCases\Domain\User;

class InMemoryUserRepository implements UserRepository
{
    private $users = [];

    public function add(User $u)
    {
        $this->users[] = $u;
    }

    public function getByEmail(string $email):?User
    {
        foreach ($this->users as $user){
            if($user->email() === $email){
                return $user;
            }
        }
        return null;
    }

    public function getById(string $id):?User
    {
        foreach ($this->users as $user){
            if($user->id() === $id){
                return $user;
            }
        }
        return null;
    }
}
