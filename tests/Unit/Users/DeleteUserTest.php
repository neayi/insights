<?php


namespace Tests\Unit\Users;


use App\Events\UserDeleted;
use App\Src\UseCases\Domain\Ports\UserRepository;
use App\Src\UseCases\Domain\User;
use App\Src\UseCases\Domain\Users\DeleteUser;
use Illuminate\Support\Facades\Artisan;
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
}
