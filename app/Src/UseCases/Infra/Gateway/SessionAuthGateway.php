<?php


namespace App\Src\UseCases\Infra\Gateway;


use App\Src\UseCases\Domain\Shared\Gateway\AuthGateway;
use App\Src\UseCases\Domain\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class SessionAuthGateway implements AuthGateway
{
    public function current(): ?User
    {
        $userModel = Auth::user();
        if(!isset($userModel)){
            return null;
        }
        $roles = $userModel->roles()->pluck('name')->toArray();
        return new User($userModel->uuid, $userModel->email, $userModel->firstname, $userModel->lastname, $userModel->organization_id, $userModel->path_picture, $roles, $userModel->providers);
    }

    public function log(User $u)
    {
        $authenticate = \App\User::where('uuid', $u->id())->first();
        Auth::login($authenticate, true);
    }

    public function wikiSessionId():? string
    {
        return Session::get('wiki_session_id');
    }

}
