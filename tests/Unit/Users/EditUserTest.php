<?php


namespace Tests\Unit\Users;


use App\Exceptions\Domain\UserNotFound;
use App\Src\Insights\Users\Application\UseCase\EditUser;
use App\Src\UseCases\Domain\User;
use Illuminate\Validation\ValidationException;
use Ramsey\Uuid\Uuid;
use Tests\TestCase;

class EditUserTest extends TestCase
{
    public function testShouldNotEditUnknownUser()
    {
        $userId = Uuid::uuid4();
        $email = 'anotheremail@gmail.com';
        $firstname = 'anotherfirstname';
        $lastname = 'anotherlastname';
        self::expectException(UserNotFound::class);
        app(EditUser::class)->edit($userId, $email, $firstname, $lastname, []);
    }

    public function testShouldEditUser()
    {
        $userId = Uuid::uuid4();
        $orgId = Uuid::uuid4();
        $user = new User($userId, 'anemail@gmail.com', 'firstname', 'lastname', $orgId);
        $this->userRepository->add($user);

        $email = 'anotheremail@gmail.com';
        $firstname = 'anotherfirstname';
        $lastname = 'anotherlastname';
        app(EditUser::class)->edit($userId, $email, $firstname, $lastname, []);

        $userExpected = new User($userId, $email, $firstname, $lastname, $orgId);
        $userSaved = $this->userRepository->getById($userId);
        self::assertEquals($userExpected, $userSaved);
    }

    public function testShouldNotEditUserWhenEmailAlreadyExist()
    {
        $userId = Uuid::uuid4();
        $user = new User($userId, 'email@gmail.com', '', '');
        $this->userRepository->add($user);

        $userId2 = Uuid::uuid4();
        $user2 = new User($userId2, 'anotheremail@gmail.com', '', '');
        $this->userRepository->add($user2);

        $email = 'anotheremail@gmail.com';
        $firstname = 'anotherfirstname';
        $lastname = 'anotherlastname';

        self::expectException(ValidationException::class);
        app(EditUser::class)->edit($userId, $email, $firstname, $lastname, []);
    }
}
