<?php


namespace App\Src\UseCases\Infra\Sql;


use App\Src\UseCases\Domain\Ports\UserRepository;
use App\Src\UseCases\Domain\User;

class UserRepositorySql implements UserRepository
{
    public function getByEmail(string $email): ?User
    {
        $record = \App\User::where('email', $email)->first();
        if(!isset($record)){
            return null;
        }
        return new User($record->uuid, $record->email, $record->firstname, $record->lastname, $record->organization_id);
    }

    public function getById(string $id): ?User
    {
        $record = \App\User::where('uuid', $id)->first();
        if(!isset($record)){
            return null;
        }
        return new User($record->uuid, $record->email, $record->firstname, $record->lastname, $record->organization_id);
    }

    public function add(User $u, string $password = null)
    {
        $userModel = new \App\User();
        $userModel->fill($u->toArray());
        $userModel->password = $password;
        $userModel->save();
    }

    public function update(User $u)
    {
        $userModel = \App\User::where('uuid', $u->id())->first();
        $userModel->fill($u->toArray());
        $userModel->save();
    }
}
