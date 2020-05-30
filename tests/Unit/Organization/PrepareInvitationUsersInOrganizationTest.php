<?php


namespace Tests\Unit\Organization;


use App\Src\UseCases\Domain\Ports\OrganizationRepository;
use App\Src\UseCases\Domain\Ports\UserRepository;
use App\Src\UseCases\Domain\PrepareInvitationUsersInOrganization;
use App\Src\UseCases\Domain\User;
use Illuminate\Support\Facades\Artisan;
use Ramsey\Uuid\Uuid;
use Tests\TestCase;

class PrepareInvitationUsersInOrganizationTest extends TestCase
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

    public function testShouldIgnoreInvalidMails()
    {
        $organizationId = Uuid::uuid4();
        $emails = ['anemail', 'anotheremail@gmail.com'];

        $usersToProcess = app(PrepareInvitationUsersInOrganization::class)->prepare($organizationId, $emails);

        $userExpectedToProcess = [['email' => 'anotheremail@gmail.com']];
        self::assertEquals($usersToProcess, $userExpectedToProcess);
    }

    public function  testShouldNotInviteWhenUserAlreadyInOrganization()
    {
        $organizationId = Uuid::uuid4();
        $emails = [$email = 'auseralreadyinOrga@gmail.com', 'anotheremail@gmail.com'];
        $user = new User(Uuid::uuid4()->toString(), $email, $organizationId);
        $this->userRepository->add($user);

        $usersToProcess = app(PrepareInvitationUsersInOrganization::class)->prepare($organizationId, $emails);

        $userExpectedToProcess = [['email' => 'anotheremail@gmail.com']];
        self::assertEquals($usersToProcess, $userExpectedToProcess);
    }
}
