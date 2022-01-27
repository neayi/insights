<?php


namespace Tests\Unit\Organization;


use App\Mail\InvitationLinkToOrganization;
use App\Src\Insights\Insights\Application\UseCase\Organizations\Invitation\InviteUsersInOrganization;
use App\Src\Insights\Insights\Domain\Organizations\Address;
use App\Src\Insights\Insights\Domain\Organizations\Invitation;
use App\Src\Insights\Insights\Domain\Organizations\Organization;
use Illuminate\Support\Facades\Mail;
use Ramsey\Uuid\Uuid;
use Tests\TestCase;

class InviteUsersInOrganizationTest extends TestCase
{
    private $address;

    public function setUp(): void
    {
        parent::setUp();
        $this->address = new Address('la garde', 'res', 'tutu', '83130');
    }

    public function test_ShouldInvite()
    {
        $organizationId = Uuid::uuid4();
        $emails = [['email' => $e1 ='anemail@gmail.com'], ['email' => $e2 = 'anotheremail@gmail.com']];

        $organization = new Organization($organizationId, 'org name', '', $this->address);
        $this->organizationRepository->add($organization);

        $result = app(InviteUsersInOrganization::class)->invite($organizationId, $emails);

        $invitationExpected = new Invitation($organizationId, $e1, '', '');

        $invitation = $this->invitationRepository->getByHash($invitationExpected->hash());
        self::assertEquals($invitationExpected, $invitation);
        Mail::assertSent(InvitationLinkToOrganization::class, 2);
        self::assertEquals($invitationExpected, $result[0]);
    }
}
