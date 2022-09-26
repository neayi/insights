<?php


namespace App\Src\UseCases\Infra\Sql;

use App\Src\UseCases\Domain\Ports\UserRepository;
use App\Src\UseCases\Domain\User;
use App\Src\UseCases\Domain\Users\Identity;
use App\Src\UseCases\Domain\Users\State;
use App\Src\UseCases\Domain\Users\Stats;
use App\Src\UseCases\Domain\Users\UserDto;
use Illuminate\Auth\Events\Verified;
use Illuminate\Support\Facades\DB;
use Ramsey\Uuid\Uuid;

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

    public function search(string $organizationId, int $page, int $perPage = 10): array
    {
        $first = DB::table('users', 'u')
            ->selectRaw('u.last_login_at, u.path_picture, u.uuid, u.id as uid, u.firstname as ufirstname, u.lastname as ulastname, u.email as uemail, invitations.id as iid, invitations.firstname as ifirstname, invitations.lastname as ilastname, invitations.email as iemail')
            ->leftJoin('invitations', 'invitations.email', 'u.email')
            ->where('u.organization_id', $organizationId);

        $records = DB::table('users', 'u')
            ->selectRaw('u.last_login_at, u.path_picture, u.uuid, u.id as uid, u.firstname as ufirstname, u.lastname as ulastname, u.email as uemail, invitations.id as iid, invitations.firstname as ifirstname, invitations.lastname as ilastname, invitations.email as iemail')
            ->rightJoin('invitations', 'invitations.email', 'u.email')
            ->where('invitations.organization_id', $organizationId)
            ->union($first)
            ->get()
        ;

        $count = $records->count();

        $records = DB::table('users', 'u')
            ->selectRaw('u.last_login_at, u.path_picture, u.uuid, u.id as uid, u.firstname as ufirstname, u.lastname as ulastname, u.email as uemail, invitations.id as iid, invitations.firstname as ifirstname, invitations.lastname as ilastname, invitations.email as iemail')
            ->rightJoin('invitations', 'invitations.email', 'u.email')
            ->where('invitations.organization_id', $organizationId)
            ->union($first)
            ->offset(($page-1)*$perPage)
            ->limit($perPage)
            ->get();
        if(empty($records)){
            return [];
        }

        $users = [];
        foreach($records as $record){
            if($record->uuid === null){
                $identity = new Identity(Uuid::uuid4(), $record->iemail, $record->ifirstname, $record->ilastname, null);
                $state = new State($organizationId, null, false, []);
                $users[] = new UserDto($identity, $state);
                continue;
            }
            $roles = \App\User::find($record->uid)->roles()->pluck('name')->toArray();
            $identity = new Identity($record->uuid, $record->uemail, $record->ufirstname, $record->ulastname, $record->path_picture);
            $state = new State($organizationId, $record->last_login_at, true, $roles);
            $users[] = new UserDto($identity, $state);
        }
        return [
            'list' => $users,
            'total' => $count
        ];
    }

    public function delete(string $userId)
    {
        $userModel = \App\User::where('uuid', $userId)->first();
        $userModel->delete();
    }

    public function getAdminOfOrganization(string $organizationId): array
    {
        $records = \App\User::role(['admin'])->where('organization_id', $organizationId)->get();
        if(empty($records)){
            return [];
        }
        $users = [];
        foreach($records as $record){
            $roles = \App\User::find($record->id)->roles()->pluck('name')->toArray();
            $users[] = new User($record->uuid, $record->email, $record->firstname, $record->lastname, $record->organization_id, $record->path_picture, $roles);
        }
        return $users;
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
            $record->discourse_id,
            $record->discourse_username
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
