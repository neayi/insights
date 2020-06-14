<?php


namespace Tests\Unit\Users;


use App\Exceptions\Domain\UserNotFound;
use App\Src\UseCases\Domain\Ports\UserRepository;
use App\Src\UseCases\Domain\User;
use App\Src\UseCases\Domain\Users\GetUser;
use Illuminate\Support\Facades\Artisan;
use Ramsey\Uuid\Uuid;
use Tests\TestCase;

class GetUserTest extends TestCase
{
    private $userRepository;

    public function setUp(): void
    {
        parent::setUp();
        $this->userRepository = app(UserRepository::class);

        if(config('app.env') === 'testing-ti'){
            Artisan::call('migrate:fresh');
        }
    }

    public function testGetUserWhoDoesNotExist()
    {
        $userId = Uuid::uuid4();
        self::expectException(UserNotFound::class);
        app(GetUser::class)->get($userId);
    }

    public function testGetUser()
    {
        $userId = Uuid::uuid4();
        $user = new User($userId, 'email@gmail.com', '', '');
        $this->userRepository->add($user);

        $userGet = app(GetUser::class)->get($userId);
        self::assertEquals($user, $userGet);
    }
}
