<?php


namespace Tests\Unit\Users;


use App\Exceptions\Domain\UserNotFound;
use App\Src\Users\Application\GetUser;
use App\Src\Users\Domain\User;
use Ramsey\Uuid\Uuid;
use Tests\TestCase;

class GetUserTest extends TestCase
{
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
