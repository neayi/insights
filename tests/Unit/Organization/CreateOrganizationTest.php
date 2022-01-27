<?php


namespace Tests\Unit\Organization;


use App\Src\Insights\Insights\Application\UseCase\Organizations\CreateOrganization;
use App\Src\Insights\Insights\Domain\Organizations\Address;
use App\Src\Insights\Insights\Domain\Organizations\Organization;
use App\Src\UseCases\Domain\Shared\Gateway\PictureHandler;
use Illuminate\Validation\ValidationException;
use Tests\TestCase;

class CreateOrganizationTest extends TestCase
{
    public function testShouldNotCreateOrganization_WhenNameMissing()
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
        app(CreateOrganization::class)->create($name, $picture, $address);
    }

    public function testShouldNotCreateOrganization_WhenAddressMissing()
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
        app(CreateOrganization::class)->create($name, $picture, $address);
    }

    public function testShouldCreateOrganization()
    {
        $name = 'organization';
        $pathPicture = 'a-path-to-the-picture.jpg';
        $picture = [
            'path_picture' => $pathPicture,
            'mime_type' => 'image/jpeg'
        ];


        app(PictureHandler::class)->add($pathPicture, 1000, 1200);

        $address = [
            'city' => $city = 'la garde',
            'address1' => $address1 = 'avenue jean',
            'address2' => $address2 = 'bat b2',
            'pc' => $pc = '83130',
        ];
        $organizationId = app(CreateOrganization::class)->create($name, $picture, $address);

        $organization = $this->organizationRepository->get($organizationId);
        $address = new Address($city, $address1, $address2, $pc);
        $organizationExpected = new Organization($organizationId, $name, 'app/public/organizations/'.$organizationId.'.jpg', $address);
        self::assertEquals($organizationExpected, $organization);

        $finalPath = storage_path().'/app/public/organizations/'.$organizationId.'.jpg';
        $width = app(PictureHandler::class)->width($finalPath);
        self::assertEquals(600, $width);
    }

    public function testShouldCreateOrganization_AndNotResizePicture()
    {
        $name = 'organization';
        $pathPicture = 'a-path-to-the-picture.jpg';
        $picture = [
            'path_picture' => $pathPicture,
            'mine_type' => 'image/jpg'
        ];

        app(PictureHandler::class)->add($pathPicture, 300, 400);

        $address = [
            'city' => $city = 'la garde',
            'address1' => $address1 = 'avenue jean',
            'address2' => $address2 = 'bat b2',
            'pc' => $pc = '83130',
        ];

        $uuid = app(CreateOrganization::class)->create($name, $picture, $address);

        $finalPath = storage_path().'/app/public/organizations/'.$uuid.'.jpg';
        $width = app(PictureHandler::class)->width($finalPath);
        self::assertEquals(300, $width);
    }

    public function testShouldCreateOrganization_AndResizeHeightPicture()
    {
        $name = 'organization';
        $pathPicture = 'a-path-to-the-picture.jpg';
        $picture = [
            'path_picture' => $pathPicture,
            'original_name' => 'pic',
            'mine_type' => 'image/jpg'
        ];

        app(PictureHandler::class)->add($pathPicture, 600, 1200);

        $address = [
            'city' => $city = 'la garde',
            'address1' => $address1 = 'avenue jean',
            'address2' => $address2 = 'bat b2',
            'pc' => $pc = '83130',
        ];

        $uuid = app(CreateOrganization::class)->create($name, $picture, $address);

        $finalPath = storage_path().'/app/public/organizations/'.$uuid.'.jpg';
        $height = app(PictureHandler::class)->height($finalPath);
        self::assertEquals(400, $height);
    }

    public function testShouldCreateOrganization_AndSavePngPicture()
    {
        $name = 'organization';
        $pathPicture = 'a-path-to-the-picture.png';
        $picture = [
            'path_picture' => $pathPicture,
            'original_name' => 'pic.png',
            'mine_type' => 'image/png'
        ];

        app(PictureHandler::class)->add($pathPicture, 600, 1200);

        $address = [
            'city' => $city = 'la garde',
            'address1' => $address1 = 'avenue jean',
            'address2' => $address2 = 'bat b2',
            'pc' => $pc = '83130',
        ];

        $uuid = app(CreateOrganization::class)->create($name, $picture, $address);

        $finalPath = storage_path().'/app/public/organizations/'.$uuid.'.png';
        $height = app(PictureHandler::class)->height($finalPath);
        self::assertEquals(400, $height);
    }

    public function testShouldNotCreateOrganization_whenPictureNotJpgOrPng()
    {
        $name = 'organization';
        $pathPicture = 'a-path-to-the-picture.png';
        $picture = [
            'path_picture' => $pathPicture,
            'original_name' => 'pic.png',
            'mine_type' => 'image/gif'
        ];

        app(PictureHandler::class)->add($pathPicture, 600, 1200);

        $address = [
            'city' => $city = 'la garde',
            'address1' => $address1 = 'avenue jean',
            'address2' => $address2 = 'bat b2',
            'pc' => $pc = '83130',
        ];

        self::expectException(ValidationException::class);
        app(CreateOrganization::class)->create($name, $picture, $address);
    }
}
