<?php


namespace Tests\Unit\Users\Profile;


use App\Src\UseCases\Domain\Ports\UserRepository;
use App\Src\UseCases\Domain\User;
use App\Src\UseCases\Domain\Users\profile\FillWikiUserProfile;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Event;
use Ramsey\Uuid\Uuid;
use Tests\TestCase;

class FillUserWikiProfileTest extends TestCase
{
    private $userRepository;

    private $userId;

    public function setUp(): void
    {
        parent::setUp();
        $this->userRepository = app(UserRepository::class);

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
        app(FillWikiUserProfile::class)->fill($this->userId, $role, $newFirstname, $newLastname);

        $userExpected = new User($this->userId, 'email@gmail.com', $newFirstname, $newLastname, null, null, ['farmer']);
        $userSaved = $this->userRepository->getById($this->userId);
        self::assertEquals($userExpected, $userSaved);
    }
}
