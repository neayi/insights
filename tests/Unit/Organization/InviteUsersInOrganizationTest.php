<?php


namespace Tests\Unit\Organization;


use App\Mail\InvitationLinkToOrganization;
use App\Src\UseCases\Domain\Address;
use App\Src\UseCases\Domain\InviteUsersInOrganization;
use App\Src\UseCases\Domain\Organization;
use App\Src\UseCases\Domain\Ports\OrganizationRepository;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Mail;
use Ramsey\Uuid\Uuid;
use Tests\TestCase;

class InviteUsersInOrganizationTest extends TestCase
{
    private $organizationRepository;
    private $address;

    public function setUp(): void
    {
        parent::setUp();
        $this->organizationRepository = app(OrganizationRepository::class);

        if(config('app.env') === 'testing-ti'){
            Artisan::call('migrate:fresh');
        }
        $this->address = new Address('la garde', 'res', 'tutu', '83130');

        Mail::fake();
    }

    public function test_ShouldInvite()
    {
        $organizationId = Uuid::uuid4();
        $emails = [['email' => 'anemail@gmail.com'], ['email' => 'anotheremail@gmail.com']];

        $organization = new Organization($organizationId, 'org name', '', $this->address);
        $this->organizationRepository->add($organization);

        app(InviteUsersInOrganization::class)->invite($organizationId, $emails);

        Mail::assertSent(InvitationLinkToOrganization::class, 2);
    }
}
