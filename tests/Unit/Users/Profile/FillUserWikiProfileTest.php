<?php


namespace Tests\Unit\Users\Profile;


use App\Src\UseCases\Domain\Agricultural\Model\Exploitation;
use App\Src\UseCases\Domain\Ports\ExploitationRepository;
use App\Src\UseCases\Domain\Ports\UserRepository;
use App\Src\UseCases\Domain\User;
use App\Src\UseCases\Domain\Users\Profile\FillWikiUserProfile;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Event;
use Illuminate\Validation\ValidationException;
use Ramsey\Uuid\Uuid;
use Tests\TestCase;

class FillUserWikiProfileTest extends TestCase
{
    private $userRepository;
    private $exploitationRepository;

    private $userId;

    public function setUp(): void
    {
        parent::setUp();
        $this->userRepository = app(UserRepository::class);
        $this->exploitationRepository = app(ExploitationRepository::class);

        if(config('app.env') === 'testing-ti'){
            Artisan::call('migrate:fresh');
        }
        Event::fake();

        $user = new User($this->userId = Uuid::uuid4(), 'email@gmail.com', 'firstname', 'lastname', null, null, []);
        $this->userRepository->add($user);
    }

    public function test_ShouldUpdateUserProfile()
    {
        $role = 'farmer';
        $newFirstname = 'newFirstname';
        $newLastname = 'newLastname';
        $postcode = '83130';
        app(FillWikiUserProfile::class)->fill($this->userId, $role, $newFirstname, $newLastname, $postcode);

        $userExpected = new User($this->userId, 'email@gmail.com', $newFirstname, $newLastname, null, null, ['farmer']);
        $userSaved = $this->userRepository->getById($this->userId);
        self::assertEquals($userExpected, $userSaved);
    }

    public function test_ShouldUpdateExploitationWithEmptyFarmingType()
    {
        $role = 'farmer';
        $newFirstname = 'newFirstname';
        $newLastname = 'newLastname';
        $postcode = '83130';
        app(FillWikiUserProfile::class)->fill($this->userId, $role, $newFirstname, $newLastname, $postcode);

        $exploitationExpected = new Exploitation(Uuid::uuid4(), $postcode, []);
        $exploitationSaved = $this->exploitationRepository->getByUser($this->userId);
        self::assertEquals($exploitationExpected, $exploitationSaved);
    }

    public function test_ShouldUpdateExploitationWithFarmingType()
    {
        $role = 'farmer';
        $newFirstname = 'newFirstname';
        $newLastname = 'newLastname';
        $postcode = '83130';
        $farmingType = [$ft1 = Uuid::uuid4(), $ft2 = Uuid::uuid4()];

        app(FillWikiUserProfile::class)->fill($this->userId, $role, $newFirstname, $newLastname, $postcode, $farmingType);

        $exploitationExpected = new Exploitation(Uuid::uuid4(), $postcode, $farmingType);
        $exploitationSaved = $this->exploitationRepository->getByUser($this->userId);
        self::assertEquals($exploitationExpected, $exploitationSaved);
    }

    /**
     * @dataProvider dataProvider
     */
    public function test_ShouldNotUpdateUserProfile($role, $newFirstname, $newLastname, $postalCode)
    {
        self::expectException(ValidationException::class);
        app(FillWikiUserProfile::class)->fill($this->userId, $role, $newFirstname, $newLastname, $postalCode);
    }

    public function dataProvider()
    {
        return [
            ['', '', '', ''],
            ['role', '', '', '83130'],
            ['role', 'firstname', '', '83130'],
            ['role', '', 'lastname' , '83130'],
            ['', 'firstname', 'lastname', '83130'],
            ['', 'firstname', 'lastname', '83130'],
            ['', 'firstname', '', '83130'],
            ['', '', 'lastname', '83130'],
            ['farmer', 'firstname', 'lastname', '0183130'],
        ];
    }
}
