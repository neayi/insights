<?php


namespace Tests\Unit\Organization;


use App\Src\UseCases\Domain\Address;
use App\Src\UseCases\Domain\CreateOrganization;
use App\Src\UseCases\Domain\Organization;
use App\Src\UseCases\Domain\Ports\OrganizationRepository;
use App\Src\UseCases\Infra\Gateway\PictureHandler;
use Illuminate\Validation\ValidationException;
use Tests\TestCase;

class SuperAdminCreateOrganizationTest extends TestCase
{
    private $organizationRepository;

    public function setUp(): void
    {
        parent::setUp();
        $this->organizationRepository = app(OrganizationRepository::class);
    }

    public function testShouldNotCreateOrganization_WhenNameMissing()
    {
        $name = '';
        $pathPicture = '';
        $address = [
            'city' => $city = 'la garde',
            'address1' => $address1 = 'avenue jean',
            'address2' => $address2 = 'bat b2',
            'pc' => $pc = '83130',
        ];
        self::expectException(ValidationException::class);
        app(CreateOrganization::class)->create($name, $pathPicture, $address);
    }

    public function testShouldNotCreateOrganization_WhenAddressMissing()
    {
        $name = 'organization';
        $pathPicture = '';
        $address = [
            'city' => $city = '',
            'address1' => $address1 = '',
            'address2' => $address2 = '',
            'pc' => $pc = '',
        ];
        self::expectException(ValidationException::class);
        app(CreateOrganization::class)->create($name, $pathPicture, $address);
    }

    public function testShouldCreateOrganization()
    {
        $name = 'organization';
        $pathPicture = 'a-path-to-the-picture.jpg';

        app(PictureHandler::class)->add($pathPicture, 1000, 1200);

        $address = [
            'city' => $city = 'la garde',
            'address1' => $address1 = 'avenue jean',
            'address2' => $address2 = 'bat b2',
            'pc' => $pc = '83130',
        ];
        $organizationId = app(CreateOrganization::class)->create($name, $pathPicture, $address);

        $organization = $this->organizationRepository->get($organizationId);
        $address = new Address($city, $address1, $address2, $pc);
        $organizationExpected = new Organization($organizationId, $name, $pathPicture, $address);
        self::assertEquals($organizationExpected, $organization);

        $finalPath = 'app/organizations/'.$organizationId.'.jpg';
        $width = app(PictureHandler::class)->width($finalPath);
        self::assertEquals(600, $width);
    }

    public function testShouldCreateOrganization_AndNotResizePicture()
    {
        $name = 'organization';
        $pathPicture = 'a-path-to-the-picture.jpg';

        app(PictureHandler::class)->add($pathPicture, 300, 400);

        $address = [
            'city' => $city = 'la garde',
            'address1' => $address1 = 'avenue jean',
            'address2' => $address2 = 'bat b2',
            'pc' => $pc = '83130',
        ];

        $uuid = app(CreateOrganization::class)->create($name, $pathPicture, $address);

        $finalPath = 'app/organizations/'.$uuid.'.jpg';
        $width = app(PictureHandler::class)->width($finalPath);
        self::assertEquals(300, $width);
    }

    public function testShouldCreateOrganization_AndResizeHeightPicture()
    {
        $name = 'organization';
        $pathPicture = 'a-path-to-the-picture.jpg';

        app(PictureHandler::class)->add($pathPicture, 600, 1200);

        $address = [
            'city' => $city = 'la garde',
            'address1' => $address1 = 'avenue jean',
            'address2' => $address2 = 'bat b2',
            'pc' => $pc = '83130',
        ];

        $uuid = app(CreateOrganization::class)->create($name, $pathPicture, $address);

        $finalPath = 'app/organizations/'.$uuid.'.jpg';
        $height = app(PictureHandler::class)->height($finalPath);
        self::assertEquals(400, $height);
    }
}
