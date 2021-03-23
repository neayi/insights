<?php


namespace Tests\Adapters\Repositories;

use App\Src\UseCases\Domain\Ports\UserRepository;
use App\Src\UseCases\Domain\User;
use App\Src\UseCases\Domain\Users\Stats;

class InMemoryUserRepository implements UserRepository
{
    private $users = [];
    private $stats = [];

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
                $users[] = $user->toDto();
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

    public function updateProviders(User $u)
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

    public function getAdminOfOrganization(string $organizationId): array
    {
        $users = [];
        foreach ($this->users as $key => $user){
            if($user->organizationId() === $organizationId && $user->isAdmin()){
                $users[] = $user;
            }
        }
        return $users;
    }

    public function getByProvider(string $provider, string $providerId): ?User
    {
        foreach ($this->users as $user){
            if($user->provider($provider, $providerId) === true){
                return $user;
            }
        }
        return null;
    }

    public function getStats(string $userId): Stats
    {
        return isset($this->stats[$userId]) ? $this->stats[$userId] : new Stats([]);
    }

    public function addStats(string $userId, Stats $stats)
    {
        $this->stats[$userId] = $stats;
    }
}
