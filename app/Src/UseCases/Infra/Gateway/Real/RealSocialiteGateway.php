<?php


namespace App\Src\UseCases\Infra\Gateway\Real;


use App\Src\UseCases\Domain\Auth\SocialiteUser;
use App\Src\UseCases\Infra\Gateway\Auth\SocialiteGateway;
use Laravel\Socialite\Facades\Socialite;

class RealSocialiteGateway implements SocialiteGateway
{
    public function user(string $provider): SocialiteUser
    {
        $user = Socialite::driver($provider)->stateless()->user();

        $email = $user->getEmail();
        $firstname = $user->user['given_name'];
        $lastname = $user->user['family_name'];
        $id = $user->getId();
        $picture = $user->user['picture'];

        return new SocialiteUser($id, $email, $firstname, $lastname, $picture);
    }
}
