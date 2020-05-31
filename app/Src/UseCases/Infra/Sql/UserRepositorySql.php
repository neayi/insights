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
        return new User($record->uuid, $record->email, $record->organization_id);
    }

    public function add(User $u)
    {
        $userModel = new \App\User();
        $userModel->fill($u->toArray());
        $userModel->save();
    }


}
