<?php


namespace Tests\Unit\Organization;


use App\Events\UserLeaveOrganization;
use App\Src\UseCases\Domain\DeleteUserFromOrganization;
use App\Src\UseCases\Domain\Ports\OrganizationRepository;
use App\Src\UseCases\Domain\Ports\UserRepository;
use App\Src\UseCases\Domain\User;
use Illuminate\Support\Facades\Event;
use Ramsey\Uuid\Uuid;
use Tests\TestCase;

class DeleteUserFromOrganizationTest extends TestCase
{
    private $organizationRepository;
    private $userRepository;

    public function setUp(): void
    {
        parent::setUp();
        $this->organizationRepository = app(OrganizationRepository::class);
        $this->userRepository = app(UserRepository::class);
        Event::fake();
    }

    public function testShouldDeleteUserFromOrganization()
    {
        $userId = Uuid::uuid4();
        $organizationId = Uuid::uuid4();
        $u = new User($userId, 'email@gmail.com', 'firstname', 'lastname', $organizationId);
        $this->userRepository->add($u);

        app(DeleteUserFromOrganization::class)->delete($userId);

        $userExpected = new User($userId, 'email@gmail.com', 'firstname', 'lastname');
        $userSaved = $this->userRepository->getById($userId);
        self::assertEquals($userExpected, $userSaved);
        Event::assertDispatched(UserLeaveOrganization::class);
    }
}
