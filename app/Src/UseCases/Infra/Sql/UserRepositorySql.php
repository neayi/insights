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
            ->select();
        $count = $records->count();

        $records = DB::table('users')
            ->select()
            ->offset(($page-1)*$perPage)
            ->limit($perPage)
            ->get();
        if(empty($records)){
            return [];
        }
        $users = [];
        foreach($records as $record){
            $roles = $record->roles()->pluck('name')->toArray();
            $users[] = new User($record->uuid, $record->email, $record->firstname, $record->lastname, $record->organization_id, $record->path_picture, $roles);
        }
        return [
            'list' => $users,
            'total' => $count
        ];
    }


}
