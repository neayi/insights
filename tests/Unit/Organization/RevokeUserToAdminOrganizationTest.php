<?php


namespace Tests\Unit\Organization;


use App\Src\Insights\Insights\Application\UseCase\Organizations\RevokeUserAsAdminOrganization;
use App\Src\UseCases\Domain\User;
use Ramsey\Uuid\Uuid;
use Tests\TestCase;

class RevokeUserToAdminOrganizationTest extends TestCase
{
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
