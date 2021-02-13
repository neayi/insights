<?php


namespace Tests\Unit\Auth;


use App\Exceptions\Domain\ProviderNotSupported;
use App\Src\UseCases\Domain\Auth\LogUserFromSocialNetwork;
use App\Src\UseCases\Domain\Auth\SocialiteUser;
use App\Src\UseCases\Domain\Ports\OrganizationRepository;
use App\Src\UseCases\Domain\Ports\UserRepository;
use App\Src\UseCases\Domain\User;
use App\Src\UseCases\Infra\Gateway\Auth\AuthGateway;
use App\Src\UseCases\Infra\Gateway\Auth\SocialiteGateway;
use Illuminate\Support\Facades\Artisan;
use Ramsey\Uuid\Uuid;
use Tests\TestCase;

class LogUserFromSocialNetworkTest extends TestCase
{
    public function testShouldNotRegisterUserWhenProviderIsNotAllowed()
    {
        self::expectException(ProviderNotSupported::class);
        app(LogUserFromSocialNetwork::class)->log('github');
    }

    public function test_ShouldLogUser()
    {
        $userSocialite = new SocialiteUser($pid = uniqid(), $email = 'anemmail@gmail.com', $first = 'first', $last = 'last');
        $this->socialiteGateway->add($userSocialite, 'facebook');

        $user = new User($id = Uuid::uuid4(), $email, $first, $last, null, '', [], ['facebook' => $pid]);
        $this->userRepository->add($user);

        app(LogUserFromSocialNetwork::class)->log($provider = 'facebook');
        $userInSession = $this->authGateway->current();

        self::assertEquals($user, $userInSession);
    }

    public function test_ShouldRegisterUserAndLog_WhenUserDoesNotExist()
    {
        $userSocialite = new SocialiteUser($pid = uniqid(), $email = 'anemmail@gmail.com', $first = 'first', $last = 'last');
        $this->socialiteGateway->add($userSocialite, 'facebook');

        app(LogUserFromSocialNetwork::class)->log($provider = 'facebook');
        $userInSession = $this->authGateway->current();

        $userSaved = $this->userRepository->getByProvider($provider, $pid);
        self::assertNotNull($userInSession);
        self::assertNotNull($userSaved);
    }
}
