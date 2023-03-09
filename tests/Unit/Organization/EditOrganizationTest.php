<?php


namespace Tests\Unit\Organization;


use App\Exceptions\Domain\OrganizationNotFound;
use App\Src\Organizations\EditOrganization;
use App\Src\Organizations\Model\Address;
use App\Src\Organizations\Model\Organization;
use Illuminate\Validation\ValidationException;
use Ramsey\Uuid\Uuid;
use Tests\TestCase;

class EditOrganizationTest extends TestCase
{
    private $organizationId;

    public function setUp(): void
    {
        parent::setUp();

        $this->organizationId = Uuid::uuid4();
        $address = new Address('1', '1', '1', '83130');
        $organization = new Organization($this->organizationId, 'name', '', $address);
        $this->organizationRepository->add($organization);
    }

    public function testShouldNotEditOrganization_WhenNameMissing()
    {
        $name = '';
        $picture = [];
        $address = [
            'city' => $city = 'la garde',
            'address1' => $address1 = 'avenue jean',
            'address2' => $address2 = 'bat b2',
            'pc' => $pc = '83130',
        ];
        self::expectException(ValidationException::class);
        app(EditOrganization::class)->edit($this->organizationId, $name, $picture, $address);
    }

    public function testShouldNotEditOrganization_WhenUnknownOrganization()
    {
        $name = '';
        $picture = [];
        $address = [
            'city' => $city = 'la garde',
            'address1' => $address1 = 'avenue jean',
            'address2' => $address2 = 'bat b2',
            'pc' => $pc = '83130',
        ];
        self::expectException(OrganizationNotFound::class);
        app(EditOrganization::class)->edit(Uuid::uuid4(), $name, $picture, $address);
    }

    public function testShouldNotEditOrganization_WhenAddressMissing()
    {
        $name = 'organization';
        $picture = [];
        $address = [
            'city' => $city = '',
            'address1' => $address1 = '',
            'address2' => $address2 = '',
            'pc' => $pc = '',
        ];
        self::expectException(ValidationException::class);
        app(EditOrganization::class)->edit($this->organizationId, $name, $picture, $address);
    }

    public function testShouldEditOrganization()
    {
        $name = 'org';
        $picture = [];
        $address = [
            'city' => $city = 'la garde',
            'address1' => $address1 = 'avenue jean',
            'address2' => $address2 = 'bat b2',
            'pc' => $pc = '83130',
        ];

        $addressExpected = new Address($city, $address1, $address2, $pc);
        $organizationExpected = new Organization($this->organizationId, $name, '', $addressExpected);
        app(EditOrganization::class)->edit($this->organizationId, $name, $picture, $address);

        $organizationSaved = $this->organizationRepository->get($this->organizationId);
        self::assertEquals($organizationExpected, $organizationSaved);
    }
}
