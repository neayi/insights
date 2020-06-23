<?php


namespace App\Src\UseCases\Infra\InMemory;


use App\Src\UseCases\Domain\Ports\UserRepository;
use App\Src\UseCases\Domain\User;

class InMemoryUserRepository implements UserRepository
{
    private $users = [];

    public function add(User $u, string $password = null)
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

    public function search(string $organizationId, int $page, int $perPage = 10): array
    {
        $users = [];
        foreach($this->users as $user){
            if($user->organizationId() === $organizationId){
                $users[] = $user;
            }
        }
        $chunks = array_chunk($users, $perPage);
        $list = isset($chunks[$page-1]) ? $chunks[$page-1] : [];
        return [
            'list' => $list,
            'total' => count($users)
        ];
    }

    public function update(User $u)
    {
        foreach ($this->users as $key => $user){
            if($user->id() === $u->id()){
                $this->users[$key] = $u;
            }
        }
    }

    public function delete(string $userId)
    {
        foreach ($this->users as $key => $user){
            if($user->id() === $userId){
                unset($this->users[$key]);
            }
        }
    }
}
