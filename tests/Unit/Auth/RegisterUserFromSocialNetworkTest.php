<?php


namespace Tests\Unit\Auth;


use App\Exceptions\Domain\ProviderNotSupported;
use App\Src\Insights\Auth\Application\UseCase\RegisterUserFromSocialNetwork;
use App\Src\Insights\Auth\Domain\SocialiteUser;
use App\Src\UseCases\Domain\User;
use Ramsey\Uuid\Uuid;
use Tests\TestCase;

class RegisterUserFromSocialNetworkTest extends TestCase
{
    public function testShouldNotRegisterUserWhenProviderIsNotAllowed()
    {
        self::expectException(ProviderNotSupported::class);
        app(RegisterUserFromSocialNetwork::class)->register('github');
    }

    public function testShouldAddProviderToUser_WhenEmailAlreadyExists()
    {
        $email = 'unemail@gmail.com';

        $socialiteUser = new SocialiteUser($fid = Uuid::uuid4(), $email, $firstname = 'first', $lastname = 'last');
        $this->socialiteGateway->add($socialiteUser, $provider = 'facebook');

        $user = new User($uid = Uuid::uuid4(), $email, $firstname, $lastname, null, null, [], ['google' => $gid = Uuid::uuid4()]);
        $this->userRepository->add($user);

        app(RegisterUserFromSocialNetwork::class)->register($provider);

        $userMerged = $this->userRepository->getByProvider($provider, $fid);
        $userExpected = new User($uid, $email, $firstname, $lastname, null, null, [], [
            'google' => $gid,
            'facebook' => $fid,
        ]);
        self::assertEquals($userExpected, $userMerged);
    }

    public function testShouldRegisterUser()
    {
        $email = 'unemail@gmail.com';
        $pictureUri = 'http://picture-uri.com/pic.jpg';

        $socialiteUser = new SocialiteUser(Uuid::uuid4(), $email, $firstname = 'first', $lastname = 'last', $pictureUri);
        $this->socialiteGateway->add($socialiteUser, 'facebook');

        $ids = app(RegisterUserFromSocialNetwork::class)->register($provider = 'facebook');
        $userExpected = new User($ids['user_id'], $email, $firstname, $lastname, null, 'app/public/users/'.$ids['user_id'].'.jpg', [], [$provider => $ids['provider_id']]);
        $userSaved = $this->userRepository->getByProvider($provider, $ids['provider_id']);
        self::assertEquals($userExpected, $userSaved);
    }

    public function testShouldNotRegisterUser_WhenUserAlreadyExist()
    {
        $email = 'unemail@gmail.com';

        $socialiteUser = new SocialiteUser($gid = Uuid::uuid4(), $email, $firstname = 'first', $lastname = 'last');
        $this->socialiteGateway->add($socialiteUser, $provider = 'google');

        $user = new User($uid = Uuid::uuid4(), $email, $firstname, $lastname, null, null, [], ['google' => $gid]);
        $this->userRepository->add($user);

        $result = app(RegisterUserFromSocialNetwork::class)->register($provider);

        self::assertEquals('user_already_exist', $result['state']);
    }

    public function testShouldRegisterUser_WithoutEmail()
    {
        $socialiteUser = new SocialiteUser($gid = Uuid::uuid4(), '', $firstname = 'first', $lastname = 'last');
        $this->socialiteGateway->add($socialiteUser, $provider = 'google');

        $user = new User($uid = Uuid::uuid4(), '', $firstname, $lastname, null, null, [], ['google' => $gid]);
        $this->userRepository->add($user);

        $ids = app(RegisterUserFromSocialNetwork::class)->register($provider);

        $userExpected = new User($ids['user_id'], '', $firstname, $lastname, null, null, [], [$provider => $ids['provider_id']]);
        $userSaved = $this->userRepository->getByProvider($provider, $ids['provider_id']);
        self::assertEquals($userExpected, $userSaved);
    }
}
