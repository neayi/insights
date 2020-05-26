<?php


namespace Tests\Unit\Organization;


use App\Src\UseCases\Domain\Address;
use App\Src\UseCases\Domain\ListOrganizations;
use App\Src\UseCases\Domain\Organization;
use App\Src\UseCases\Domain\Ports\OrganizationRepository;
use Illuminate\Support\Facades\Artisan;
use Ramsey\Uuid\Uuid;
use Tests\TestCase;

class ListOrganizationsTest extends TestCase
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
    }

    public function testShouldBeEmptyList()
    {
        $organizations = app(ListOrganizations::class)->list($page = 1, $perPage = 2);
        self::assertEmpty($organizations);
    }

    public function testShouldListOrganization()
    {
        $o = new Organization(Uuid::uuid4(), 'org', '', $this->address);
        $this->organizationRepository->add($o);

        $organizations = app(ListOrganizations::class)->list($page = 1, $perPage = 2);
        self::assertContainsOnlyInstancesOf(Organization::class, $organizations);
        self::assertCount(1, $organizations);
    }

    public function testShouldPageOfOrganization()
    {
        $o = new Organization(Uuid::uuid4(), 'org', '', $this->address);
        $this->organizationRepository->add($o);
        $o = new Organization(Uuid::uuid4(), 'org', '', $this->address);
        $this->organizationRepository->add($o);
        $o = new Organization(Uuid::uuid4(), 'org', '', $this->address);
        $this->organizationRepository->add($o);

        $organizations = app(ListOrganizations::class)->list($page = 1, $perPage = 2);
        self::assertContainsOnlyInstancesOf(Organization::class, $organizations);
        self::assertCount(2, $organizations);

        $organizations = app(ListOrganizations::class)->list($page = 2, $perPage = 2);
        self::assertContainsOnlyInstancesOf(Organization::class, $organizations);
        self::assertCount(1, $organizations);
    }
}
