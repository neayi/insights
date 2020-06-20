<?php


namespace Tests\Unit\Organization;


use App\Mail\UserJoinsOrganizationToUser;
use App\Src\UseCases\Domain\Address;
use App\Src\UseCases\Domain\Invitation\AttachUserToAnOrganization;
use App\Src\UseCases\Domain\Organization;
use App\Src\UseCases\Domain\Ports\OrganizationRepository;
use App\Src\UseCases\Domain\Ports\UserRepository;
use App\Src\UseCases\Domain\User;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Mail;
use Ramsey\Uuid\Uuid;
use Tests\TestCase;

class AttachUserToAnOrganizationTest extends TestCase
{
    private $organizationRepository;
    private $userRepository;
    private $address;

    public function setUp(): void
    {
        parent::setUp();
        $this->organizationRepository = app(OrganizationRepository::class);
        $this->userRepository = app(UserRepository::class);

        if(config('app.env') === 'testing-ti'){
            Artisan::call('migrate:fresh');
        }
        $this->address = new Address('la garde', 'res', 'tutu', '83130');
        Mail::fake();
    }

    public function testShouldAttachToAnOrganization()
    {
        $organizationId = Uuid::uuid4();
        $organization = new Organization($organizationId, 'name', '', $this->address);
        $this->organizationRepository->add($organization);

        $userId = Uuid::uuid4();
        $user = new User($userId, 'anemail@gmail.com', 'firstname', 'lastname');
        $this->userRepository->add($user);

        app(AttachUserToAnOrganization::class)->attach($userId, $organizationId);

        $userExpected = new User($userId, 'anemail@gmail.com', 'firstname', 'lastname', $organizationId);
        self::assertEquals($userExpected, $user);

        Mail::assertSent(UserJoinsOrganizationToUser::class);
    }

}
