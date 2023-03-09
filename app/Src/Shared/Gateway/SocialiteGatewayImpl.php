<?php


namespace App\Src\Shared\Gateway;


use App\Src\Auth\SocialiteUser;
use Laravel\Socialite\Facades\Socialite;

class SocialiteGatewayImpl implements SocialiteGateway
{
    public function user(string $provider): SocialiteUser
    {
        if($provider === 'twitter'){
            $user = Socialite::driver($provider)->user();
        }elseif($provider === 'facebook'){
            $user = Socialite::driver($provider)->fields(['name', 'first_name', 'last_name', 'email'])->user();
        }else {
            $user = Socialite::driver($provider)->stateless()->user();
        }

        $email = $user->getEmail();

        // For twitter, we prefer to use the name. If empty we'll use the twitter account (nickname):
        $firstname = $user->getName() !== null ? $user->getName() :  $user->getNickname();
        if(!empty($user->user['given_name'])){
            // Google
            $firstname = $user->user['given_name'];
        }elseif (!empty($user->user['first_name'])){
            // Facebook
            $firstname = $user->user['first_name'];
        }

        $lastname = $user->getName();
        if(!empty($user->user['family_name'])){
            // Google
            $lastname = $user->user['family_name'];
        }elseif (!empty($user->user['last_name'])){
            // Facebook
            $lastname = $user->user['last_name'];
        }

        $id = $user->getId();
        $picture = $user->getAvatar();

        return new SocialiteUser($id, $email, $firstname, $lastname, $picture);
    }
}
