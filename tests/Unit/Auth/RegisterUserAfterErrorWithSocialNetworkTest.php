<?php


namespace Tests\Unit\Auth;


use App\Exceptions\Domain\ProviderMissing;
use App\Src\UseCases\Domain\Auth\RegisterUserAfterErrorWithSocialNetwork;
use App\Src\UseCases\Domain\Ports\OrganizationRepository;
use App\Src\UseCases\Domain\Ports\UserRepository;
use App\Src\UseCases\Domain\User;
use App\Src\UseCases\Infra\Gateway\Auth\AuthGateway;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Validation\ValidationException;
use Ramsey\Uuid\Uuid;
use Tests\TestCase;

class RegisterUserAfterErrorWithSocialNetworkTest extends TestCase
{
    private $organizationRepository;
    private $authGateway;
    private $userRepository;

    public function setUp(): void
    {
        parent::setUp();
        $this->organizationRepository = app(OrganizationRepository::class);
        $this->userRepository = app(UserRepository::class);
        $this->authGateway = app(AuthGateway::class);

        if(config('app.env') === 'testing-ti'){
            Artisan::call('migrate:fresh');
        }
    }

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
