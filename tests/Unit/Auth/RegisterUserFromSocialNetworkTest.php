<?php


namespace Tests\Unit\Auth;


use App\Exceptions\Domain\ProviderNotSupported;
use App\Src\UseCases\Domain\Auth\RegisterUserFromSocialNetwork;
use App\Src\UseCases\Domain\Auth\SocialiteUser;
use App\Src\UseCases\Domain\Ports\OrganizationRepository;
use App\Src\UseCases\Domain\Ports\UserRepository;
use App\Src\UseCases\Domain\User;
use App\Src\UseCases\Infra\Gateway\Auth\SocialiteGateway;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Validation\ValidationException;
use Ramsey\Uuid\Uuid;
use Tests\TestCase;

class RegisterUserFromSocialNetworkTest extends TestCase
{
    private $organizationRepository;
    private $userRepository;
    private $socialiteGateway;

    public function setUp(): void
    {
        parent::setUp();
        $this->organizationRepository = app(OrganizationRepository::class);
        $this->userRepository = app(UserRepository::class);
        $this->socialiteGateway = app(SocialiteGateway::class);

        if(config('app.env') === 'testing-ti'){
            Artisan::call('migrate:fresh');
        }

    }

    public function testShouldNotRegisterUserWhenProviderIsNotAllowed()
    {
        self::expectException(ProviderNotSupported::class);
        app(RegisterUserFromSocialNetwork::class)->register('github');
    }

    public function testShouldNotRegisterUser_WhenEmailAlreadyExists()
    {
        $email = 'unemail@gmail.com';

        $socialiteUser = new SocialiteUser(Uuid::uuid4(), $email, $firstname = 'first', $lastname = 'last');
        $this->socialiteGateway->add($socialiteUser, 'facebook');

        $user = new User(Uuid::uuid4(), $email, $firstname, $lastname);
        $this->userRepository->add($user);

        self::expectException(ValidationException::class);
        app(RegisterUserFromSocialNetwork::class)->register('facebook');
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

}
