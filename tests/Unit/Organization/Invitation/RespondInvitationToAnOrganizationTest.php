<?php


namespace Tests\Unit\Organization\Invitation;


use App\Src\UseCases\Domain\Address;
use App\Src\UseCases\Domain\Invitation\RespondInvitationToAnOrganization;
use App\Src\UseCases\Domain\Organization;
use App\Src\UseCases\Domain\Ports\OrganizationRepository;
use App\Src\UseCases\Domain\Ports\UserRepository;
use App\Src\UseCases\Domain\User;
use Illuminate\Support\Facades\Artisan;
use Ramsey\Uuid\Uuid;
use Tests\TestCase;

class RespondInvitationToAnOrganizationTest extends TestCase
{
    private $organizationRepository;
    private $userRepository;

    public function setUp(): void
    {
        parent::setUp();
        $this->organizationRepository = app(OrganizationRepository::class);
        $this->userRepository = app(UserRepository::class);

        if(config('app.env') === 'testing-ti'){
            Artisan::call('migrate:fresh');
        }
    }

    public function testShouldAskUserToRegisterWhenItDoesNotExist()
    {
        $organizationId = Uuid::uuid4();
        $email = 'anemailofunknowuser@gmail.com';
        $token = base64_encode($organizationId.'|*|'.$email.'|*||*|');

        $action = app(RespondInvitationToAnOrganization::class)->respond($token);

        $actionExpected = [
            'action' => 'register',
            'organization_id' => $organizationId->toString(),
            'user' => [
                'email' => $email,
                'firstname' => '',
                'lastname' => '',
            ]
        ];
        self::assertEquals($actionExpected, $action);
    }

    public function testShouldAskUserToAcceptOrDeclineInvitation()
    {
        $organizationId = Uuid::uuid4();
        $email = 'user@gmail.com';

        $userId = Uuid::uuid4();
        $user = new User($userId, $email, 'firstname', 'lastname');
        $this->userRepository->add($user);

        $token = base64_encode($organizationId.'|*|'.$email.'|*||*|');
        $action = app(RespondInvitationToAnOrganization::class)->respond($token);

        $actionExpected = [
            'action' => 'accept_or_decline'
        ];
        self::assertEquals($actionExpected, $action);
    }
}
