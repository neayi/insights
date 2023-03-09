<?php


namespace Tests\Unit\Organization\Invitation;


use App\Mail\UserJoinsOrganizationToAdmin;
use App\Mail\UserJoinsOrganizationToUser;
use App\Src\Organizations\Invitation\AttachUserToAnOrganization;
use App\Src\Organizations\Model\Address;
use App\Src\Organizations\Model\Organization;
use App\Src\Users\User;
use Illuminate\Support\Facades\Mail;
use Ramsey\Uuid\Uuid;
use Tests\TestCase;

class AttachUserToAnOrganizationTest extends TestCase
{
    private $address;

    public function setUp(): void
    {
        parent::setUp();
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

        $this->addAdminOrganization1($organizationId);
        $this->addAdminOrganization2($organizationId);

        app(AttachUserToAnOrganization::class)->attach($userId, $organizationId);

        $userExpected = new User($userId, 'anemail@gmail.com', 'firstname', 'lastname', $organizationId);
        $user = $this->userRepository->getById($userId);
        self::assertEquals($userExpected, $user);

        $this->assertMailSendToUser();
        $this->assertMailSentToAdminOrganization();
    }

    /**
     * @param \Ramsey\Uuid\UuidInterface $organizationId
     */
    private function addAdminOrganization1(\Ramsey\Uuid\UuidInterface $organizationId): void
    {
        $adminId = Uuid::uuid4();
        $admin = new User($adminId, 'anemail@gmail.com', 'firstname', 'lastname', $organizationId, '', ['admin']);
        $this->userRepository->add($admin);
    }

    /**
     * @param \Ramsey\Uuid\UuidInterface $organizationId
     */
    private function addAdminOrganization2(\Ramsey\Uuid\UuidInterface $organizationId): void
    {
        $adminId2 = Uuid::uuid4();
        $admin2 = new User($adminId2, 'anemail@gmail.com', 'firstname', 'lastname', $organizationId, '', ["admin"]);
        $this->userRepository->add($admin2);
    }

    private function assertMailSentToAdminOrganization(): void
    {
        Mail::assertSent(UserJoinsOrganizationToAdmin::class, 2);
    }

    private function assertMailSendToUser(): void
    {
        Mail::assertSent(UserJoinsOrganizationToUser::class);
    }

}
