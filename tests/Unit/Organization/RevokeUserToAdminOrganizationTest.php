<?php


namespace Tests\Unit\Organization;


use App\Src\UseCases\Domain\Ports\OrganizationRepository;
use App\Src\UseCases\Domain\Ports\UserRepository;
use App\Src\UseCases\Domain\User;
use App\Src\UseCases\Organizations\RevokeUserAsAdminOrganization;
use Ramsey\Uuid\Uuid;
use Tests\TestCase;

class RevokeUserToAdminOrganizationTest extends TestCase
{
    private $organizationRepository;
    private $userRepository;

    public function setUp(): void
    {
        parent::setUp();
        $this->organizationRepository = app(OrganizationRepository::class);
        $this->userRepository = app(UserRepository::class);
    }

    public function testShouldNotGrantUserAsAdmin_WhenHeDoesNotBelongToOrganization()
    {
        $organizationId = Uuid::uuid4();
        $user = new User($uid = Uuid::uuid4(), 'email@gmail.com', 'first', 'last', $organizationId, '', ['admin']);
        $this->userRepository->add($user);

        app(RevokeUserAsAdminOrganization::class)->revoke($uid, $organizationId);

        $userExpected = new User($uid, 'email@gmail.com', 'first', 'last', $organizationId, '', []);
        $userSaved = $this->userRepository->getById($uid);
        self::assertEquals($userExpected, $userSaved);
    }
}
