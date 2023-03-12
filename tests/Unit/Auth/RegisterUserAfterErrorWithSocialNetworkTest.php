<?php


namespace Tests\Unit\Auth;


use App\Exceptions\Domain\ProviderMissing;
use App\Src\Auth\RegisterUserAfterErrorWithSocialNetwork;
use App\Src\Users\Domain\User;
use Illuminate\Validation\ValidationException;
use Ramsey\Uuid\Uuid;
use Tests\TestCase;

class RegisterUserAfterErrorWithSocialNetworkTest extends TestCase
{
    public function testShouldRegisterUser()
    {
        $firstname = 'first';
        $lastname = 'first';
        $email = 'email@gmail.com';
        $provider = 'facebook';
        $providerId = '1';
        $pictureUrl = 'http://picture-uri.com/pic.jpg';

        $ids = app(RegisterUserAfterErrorWithSocialNetwork::class)->register($firstname, $lastname, $email, $provider, $providerId, $pictureUrl);

        $userExpected = new User($ids['user_id'], $email, $firstname, $lastname, null, 'app/public/users/'.$ids['user_id'].'.jpg', [], [$provider => $providerId]);
        $userSaved = $this->userRepository->getByProvider($provider, $providerId);
        self::assertEquals($userExpected, $userSaved);

        $userLogged = $this->authGateway->current();
        self::assertEquals($userExpected, $userLogged);
    }

    public function testNotShouldRegisterUser_WhenProviderDataMissing()
    {
        $firstname = 'first';
        $lastname = 'first';
        $email = 'email@gmail.com';
        $provider = null;
        $providerId = null;
        $pictureUrl = 'http://picture-uri.com/pic.jpg';

        self::expectException(ProviderMissing::class);
        app(RegisterUserAfterErrorWithSocialNetwork::class)->register($firstname, $lastname, $email, $provider, $providerId, $pictureUrl);
    }

    public function testNotShouldRegisterUser_WhenDataInvalid()
    {
        $firstname = 'first';
        $lastname = '';
        $email = 'email';
        $provider = 'facebook';
        $providerId = 'abc';
        $pictureUrl = 'http://picture-uri.com/pic.jpg';

        self::expectException(ValidationException::class);
        app(RegisterUserAfterErrorWithSocialNetwork::class)->register($firstname, $lastname, $email, $provider, $providerId, $pictureUrl);
    }

    public function testShouldAddProviderToUser_WhenEmailAlreadyExists()
    {
        $email = 'unemail@gmail.com';
        $firstname = 'first';
        $lastname = 'first';
        $provider = 'facebook';
        $providerId = 'abc';
        $pictureUrl = 'http://picture-uri.com/pic.jpg';

        $user = new User($uid = Uuid::uuid4(), $email, $firstname, $lastname);
        $this->userRepository->add($user);

        app(RegisterUserAfterErrorWithSocialNetwork::class)->register($firstname, $lastname, $email, $provider, $providerId, $pictureUrl);

        $userMerged = $this->userRepository->getByProvider($provider, $providerId);
        $userExpected = new User($uid, $email, $firstname, $lastname, null, null, [], [
            'facebook' => $providerId,
        ]);
        self::assertEquals($userExpected, $userMerged);
    }
}
