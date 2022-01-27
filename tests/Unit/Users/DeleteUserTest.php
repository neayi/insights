<?php


namespace Tests\Unit\Users;


use App\Events\UserDeleted;
use App\Exceptions\Domain\UserNotFound;
use App\Src\Insights\Users\Application\UseCase\DeleteUser;
use App\Src\UseCases\Domain\User;
use Illuminate\Support\Facades\Event;
use Ramsey\Uuid\Uuid;
use Tests\TestCase;

class DeleteUserTest extends TestCase
{
    public function testShouldDeleteUser()
    {
        $userId = Uuid::uuid4();
        $user = new User($userId, 'email@gmail.com', 'first', 'last');
        $this->userRepository->add($user);

        app(DeleteUser::class)->delete($userId);

        $userDeleted = $this->userRepository->getById($userId);
        self::assertNull($userDeleted);

        Event::assertDispatched(UserDeleted::class);
    }

    public function testShouldNotDeleteUserWhenDoesNotExist()
    {
        self::expectException(UserNotFound::class);
        app(DeleteUser::class)->delete($userId = 'abc');
    }
}
