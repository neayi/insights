<?php


namespace Tests\Unit\Organization;


use App\Mail\InvitationLinkToOrganization;
use App\Src\UseCases\Domain\InviteUsersInOrganization;
use App\Src\UseCases\Domain\Ports\OrganizationRepository;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Mail;
use Ramsey\Uuid\Uuid;
use Tests\TestCase;

class InviteUsersInOrganizationTest extends TestCase
{
    private $organizationRepository;

    public function setUp(): void
    {
        parent::setUp();
        $this->organizationRepository = app(OrganizationRepository::class);

        if(config('app.env') === 'testing-ti'){
            Artisan::call('migrate:fresh');
        }
        Mail::fake();
    }

    public function test_ShouldInvite()
    {
        $organizationId = Uuid::uuid4();
        $emails = [['email' => 'anemail@gmail.com'], ['email' => 'anotheremail@gmail.com']];

        app(InviteUsersInOrganization::class)->invite($organizationId, $emails);

        Mail::assertSent(InvitationLinkToOrganization::class, 2);
    }
}
