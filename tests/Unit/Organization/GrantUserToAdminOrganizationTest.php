<?php


namespace Tests\Unit\Organization;


use App\Exceptions\Domain\UserGrantAdminException;
use App\Src\UseCases\Domain\Ports\OrganizationRepository;
use App\Src\UseCases\Domain\Ports\UserRepository;
use App\Src\UseCases\Domain\User;
use App\Src\UseCases\Organizations\GrantUserAsAdminOrganization;
use Ramsey\Uuid\Uuid;
use Tests\TestCase;

class GrantUserToAdminOrganizationTest extends TestCase
{
    public function testShouldNotGrantUserAsAdmin_WhenHeDoesNotBelongToOrganization()
    {
        $user = new User($uid = Uuid::uuid4(), 'email@gmail.com', 'first', 'last');
        $this->userRepository->add($user);

        $organizationId = Uuid::uuid4();
        self::expectException(UserGrantAdminException::class);
        app(GrantUserAsAdminOrganization::class)->grant($uid, $organizationId);
    }

    public function testShouldGrantUserAsAdmin()
    {
        $organizationId = Uuid::uuid4();
        $user = new User($uid = Uuid::uuid4(), 'email@gmail.com', 'first', 'last', $organizationId);
        $this->userRepository->add($user);

        app(GrantUserAsAdminOrganization::class)->grant($uid, $organizationId);

        $userExpected = new User($uid, 'email@gmail.com', 'first', 'last', $organizationId, '', ['admin']);
        $userSaved = $this->userRepository->getById($uid);
        self::assertEquals($userExpected, $userSaved);
    }


}
