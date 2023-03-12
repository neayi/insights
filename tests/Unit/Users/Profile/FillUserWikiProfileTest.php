<?php


namespace Tests\Unit\Users\Profile;


use App\Src\Context\Domain\Context;
use App\Src\Shared\IdentityProvider;
use App\Src\Users\Application\FillWikiUserProfile;
use App\Src\Users\Domain\User;
use Illuminate\Validation\ValidationException;
use Ramsey\Uuid\Uuid;
use Tests\TestCase;

class FillUserWikiProfileTest extends TestCase
{
    private $userId;

    public function setUp(): void
    {
        parent::setUp();
        $user = new User($this->userId = Uuid::uuid4(), 'email@gmail.com', 'firstname', 'lastname', null, null, []);
        $this->userRepository->add($user);
    }

    public function test_ShouldUpdateUserProfile()
    {
        $role = 'farmer';
        $newFirstname = 'newFirstname';
        $newLastname = 'newLastname';
        $postcode = '83130';
        $email = 'e@email.com';
        app(FillWikiUserProfile::class)->fill($this->userId, $role, $newFirstname, $newLastname, $email, $postcode);

        $userExpected = new User($this->userId, $email, $newFirstname, $newLastname, null, null, ['farmer']);
        $userSaved = $this->userRepository->getById($this->userId);
        self::assertEquals($userExpected, $userSaved);
    }

    public function test_ShouldUpdateContextWithEmptyFarmingType()
    {
        $role = 'farmer';
        $newFirstname = 'newFirstname';
        $newLastname = 'newLastname';
        $postcode = '83130';
        $email = 'e@email.com';
        $identityProvider = app(IdentityProvider::class);
        $identityProvider->setId($exploitationId = Uuid::uuid4());

        app(FillWikiUserProfile::class)->fill($this->userId, $role, $newFirstname, $newLastname, $email, $postcode);

        $coordinates = [43, 117];
        $contextExpected = new Context($exploitationId, $postcode, [], null, null, null, '83', $coordinates);
        $contextSaved = $this->contextRepository->getByUser($this->userId);
        self::assertEquals($contextExpected, $contextSaved);
    }

    public function test_ShouldUpdateContextWithFarmingType()
    {
        $role = 'farmer';
        $newFirstname = 'newFirstname';
        $newLastname = 'newLastname';
        $postcode = '83130';
        $email = 'e@email.com';
        $farmingType = [$ft1 = Uuid::uuid4(), $ft2 = Uuid::uuid4()];

        $identityProvider = app(IdentityProvider::class);
        $identityProvider->setId($exploitationId = Uuid::uuid4());

        app(FillWikiUserProfile::class)->fill($this->userId, $role, $newFirstname, $newLastname, $email, $postcode, $farmingType);

        $coordinates = [43, 117];
        $exploitationExpected = new Context($exploitationId, $postcode, $farmingType, null, null, null, '83', $coordinates);
        $exploitationSaved = $this->contextRepository->getByUser($this->userId);
        self::assertEquals($exploitationExpected, $exploitationSaved);
    }

    /**
     * @dataProvider dataProvider
     */
    public function test_ShouldNotUpdateUserProfile($role, $newFirstname, $newLastname, $email, $postalCode)
    {
        $user = new User(Uuid::uuid4(), 'useremail@gmail.com', 'first', 'last');
        $this->userRepository->add($user);

        self::expectException(ValidationException::class);
        app(FillWikiUserProfile::class)->fill($this->userId, $role, $newFirstname, $newLastname, $email, $postalCode);
    }

    public function dataProvider()
    {
        return [
            ['', '', '', '', ''],
            ['role', '', '', 'e@email','83130'],
            ['role', 'firstname', '', 'e@email.com', '83130'],
            ['role', '', 'lastname' ,  'e@email.com','83130'],
            ['', 'firstname', 'lastname',  'e@email.com', '83130'],
            ['', 'firstname', 'lastname',  'e@email.com', '83130'],
            ['', 'firstname', '',  'e@email.com', '83130'],
            ['', '', 'lastname',  'e@email.com', '83130'],
            ['farmer', 'firstname', 'lastname',  'e@email.com', '0183130'],
            ['farmer', 'firstname', 'lastname',  'useremail@gmail.com', '83130'],
        ];
    }
}
