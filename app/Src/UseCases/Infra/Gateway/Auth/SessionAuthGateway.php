<?php


namespace App\Src\UseCases\Infra\Gateway\Auth;


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
        // TODO: Implement log() method.
    }

}
