<?php


namespace App\Src\UseCases\Infra\Sql;

use App\Src\UseCases\Domain\Ports\UserRepository;
use App\Src\UseCases\Domain\User;
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
        return new User($record->uuid, $record->email, $record->firstname, $record->lastname, $record->organization_id, $record->path_picture, $roles);
    }

    public function getById(string $id): ?User
    {
        $record = \App\User::where('uuid', $id)->first();
        if(!isset($record)){
            return null;
        }
        $roles = $record->roles()->pluck('name')->toArray();
        return new User($record->uuid, $record->email, $record->firstname, $record->lastname, $record->organization_id, $record->path_picture, $roles);
    }

    public function add(User $u, string $password = null)
    {
        $userModel = new \App\User();
        $data = $u->toArray();
        unset($data['url_picture']);
        unset($data['roles']);
        $userModel->fill($data);
        $userModel->password = $password;
        $userModel->save();
    }

    public function update(User $u)
    {
        $userModel = \App\User::where('uuid', $u->id())->first();
        $data = $u->toArray();
        $roles = $data['roles'];
        unset($data['url_picture']);
        unset($data['roles']);
        $userModel->fill($data);
        $userModel->save();

        $userModel->syncRoles($roles);
    }

    public function search(string $organizationId, int $page, int $perPage = 10): array
    {
        $records = DB::table('users')
            ->select()
            ->where('organization_id', $organizationId)
        ;
        $count = $records->count();

        $records = DB::table('users')
            ->select()
            ->where('organization_id', $organizationId)
            ->offset(($page-1)*$perPage)
            ->limit($perPage)
            ->get();
        if(empty($records)){
            return [];
        }
        $users = [];
        foreach($records as $record){
            $roles = \App\User::find($record->id)->roles()->pluck('name')->toArray();
            $users[] = new User($record->uuid, $record->email, $record->firstname, $record->lastname, $record->organization_id, $record->path_picture, $roles);
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


}
