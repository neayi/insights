<?php


namespace Tests\Unit\Organization;


use App\Src\Insights\Insights\Application\Read\Organizations\ListOrganizations;
use App\Src\Insights\Insights\Domain\Organizations\Address;
use App\Src\Insights\Insights\Domain\Organizations\Organization;
use Ramsey\Uuid\Uuid;
use Tests\TestCase;

class ListOrganizationsTest extends TestCase
{
    private $address;

    public function setUp(): void
    {
        parent::setUp();
        $this->address = new Address('la garde', 'res', 'tutu', '83130');
    }

    public function testShouldBeEmptyList()
    {
        $organizations = app(ListOrganizations::class)->list($page = 1, $perPage = 2);
        self::assertEmpty($organizations['list']);
    }

    public function testShouldListOrganization()
    {
        $o = new Organization(Uuid::uuid4(), 'org', '', $this->address);
        $this->organizationRepository->add($o);

        $organizations = app(ListOrganizations::class)->list($page = 1, $perPage = 2);
        self::assertContainsOnlyInstancesOf(Organization::class, $organizations['list']);
        self::assertCount(1, $organizations['list']);
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
        self::assertContainsOnlyInstancesOf(Organization::class, $organizations['list']);
        self::assertCount(2, $organizations['list']);

        $organizations = app(ListOrganizations::class)->list($page = 2, $perPage = 2);
        self::assertContainsOnlyInstancesOf(Organization::class, $organizations['list']);
        self::assertCount(1, $organizations['list']);
    }
}
