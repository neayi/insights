<?php


namespace App\Src\UseCases\Infra\Gateway;


use App\Src\UseCases\Domain\Auth\SocialiteUser;
use App\Src\UseCases\Domain\Shared\Gateway\SocialiteGateway;
use Laravel\Socialite\Facades\Socialite;

class RealSocialiteGateway implements SocialiteGateway
{
    public function user(string $provider): SocialiteUser
    {
        if($provider === 'twitter'){
            $user = Socialite::driver($provider)->user();
        }else {
            $user = Socialite::driver($provider)->stateless()->user();
        }

        $email = $user->getEmail();
        $firstname = $user->getNickname() !== null ? $user->getNickname() : $user->getName();
        if(isset($user->user['given_name'])){
            $firstname = $user->user['given_name'];
        }
        $lastname = isset($user->user['family_name']) ? $user->user['family_name'] : $user->getName();
        $id = $user->getId();
        $picture = $user->getAvatar();

        return new SocialiteUser($id, $email, $firstname, $lastname, $picture);
    }
}
