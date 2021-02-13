<?php


namespace App\Src\UseCases\Infra\Gateway;


use App\Src\UseCases\Domain\Shared\Gateway\AuthGateway;
use App\Src\UseCases\Domain\User;
use Illuminate\Support\Facades\Auth;

class SessionAuthGateway implements AuthGateway
{
    public function current(): ?User
    {
        $userModel = Auth::user();
        if(!isset($userModel)){
            return null;
        }
        return new User($userModel->uuid, $userModel->email, $userModel->firstname, $userModel->lastname, $userModel->organization_id);
    }

    public function log(User $u)
    {
        $autenticable = \App\User::where('uuid', $u->id())->first();
        Auth::login($autenticable);
    }

}
