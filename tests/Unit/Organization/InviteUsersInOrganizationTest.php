<?php


namespace Tests\Unit\Organization;


use App\Mail\InvitationLinkToOrganization;
use App\Src\UseCases\Domain\Address;
use App\Src\UseCases\Domain\Invitation;
use App\Src\UseCases\Domain\InviteUsersInOrganization;
use App\Src\UseCases\Domain\Organization;
use App\Src\UseCases\Domain\Ports\InvitationRepository;
use App\Src\UseCases\Domain\Ports\OrganizationRepository;
use App\Src\Utils\Hash\HashGen;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Ramsey\Uuid\Uuid;
use Tests\TestCase;

class InviteUsersInOrganizationTest extends TestCase
{
    private $organizationRepository;
    private $invitationRepository;
    private $address;

    public function setUp(): void
    {
        parent::setUp();
        $this->organizationRepository = app(OrganizationRepository::class);
        $this->invitationRepository = app(InvitationRepository::class);

        if(config('app.env') === 'testing-ti'){
            Artisan::call('migrate:fresh');
        }
        $this->address = new Address('la garde', 'res', 'tutu', '83130');

        Mail::fake();
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
