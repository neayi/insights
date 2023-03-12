<?php


namespace App\Src\Users\Infrastructure;

use App\Src\Users\Stats;
use App\Src\Users\User;
use App\Src\Users\UserRepository;
use Illuminate\Auth\Events\Verified;
use Illuminate\Support\Facades\DB;

class UserRepositorySql implements UserRepository
{
    public function getByEmail(string $email): ?User
    {
        $record = \App\User::where('email', $email)->first();
        if(!isset($record)){
            return null;
        }
        $roles = $record->roles()->pluck('name')->toArray();
        return $this->buildUser($record, $roles);
    }

    public function getById(string $id): ?User
    {
        $record = \App\User::where('uuid', $id)->first();
        if(!isset($record)){
            return null;
        }
        $roles = $record->roles()->pluck('name')->toArray();
        return $this->buildUser($record, $roles);
    }

    public function add(User $u, string $password = null)
    {
        $userModel = new \App\User();
        $data = $u->toArray();
        $roles = $data['roles'];
        unset($data['url_picture']);
        unset($data['roles']);
        $userModel->fill($data);
        $userModel->password = $password;
        $userModel->save();

        $userModel->syncRoles($roles);
    }

    public function update(User $u)
    {
        $userModel = \App\User::where('uuid', $u->id())->first();
        $oldEmail = $userModel->email;

        $data = $u->toArray();
        $roles = $data['roles'];
        unset($data['url_picture']);
        unset($data['roles']);
        $userModel->fill($data);

        if($oldEmail !== $data['email']){
            $userModel->email_verified_at = null;
            $userModel->sendEmailVerificationNotification();
        }
        $userModel->save();
        $userModel->syncRoles($roles);
    }

    public function updateProviders(User $u)
    {
        $userModel = \App\User::where('uuid', $u->id())->first();
        $providers = $u->toArray()['providers'];
        $userModel->providers = $providers;
        $userModel->save();
    }

    public function delete(string $userId)
    {
        $userModel = \App\User::where('uuid', $userId)->first();
        $userModel->delete();
    }

    public function getByProvider(string $provider, string $providerId): ?User
    {
        $record = \App\User::where('providers->'.$provider, $providerId)->first();
        if(!isset($record)){
            return null;
        }
        $roles = $record->roles()->pluck('name')->toArray();
        return new User($record->uuid, $record->email, $record->firstname, $record->lastname, $record->organization_id, $record->path_picture, $roles, $record->providers);
    }

    public function getStats(string $userId): Stats
    {
        $record = \App\User::where('uuid', $userId)->first();
        if(isset($record) && $record->wiki_stats !== null){
            return new Stats($record->wiki_stats);
        }
        return new Stats([]);
    }

    public function addStats(string $userId, Stats $stats)
    {
        DB::table('users')
            ->where('uuid', $userId)
            ->update(['wiki_stats' => $stats->toArray()]);
    }

    /**
     * @param $record
     * @param $roles
     * @return User
     */
    private function buildUser($record, $roles): User
    {
        return new User(
            $record->uuid,
            $record->email,
            $record->firstname,
            $record->lastname,
            $record->organization_id,
            $record->path_picture,
            $roles,
            $record->providers ?? [],
            $record->discourse_id ?? '',
            $record->discourse_username ?? ''
        );
    }

    public function verifyEmail(string $userId)
    {
        $user = \App\User::where('uuid', $userId)->first();
        if(!isset($user)){
           return;
        }
        event(new Verified($user));
        $user->markEmailAsVerified();
    }
}
