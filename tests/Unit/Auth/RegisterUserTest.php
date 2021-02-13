<?php


namespace Tests\Unit\Auth;


use App\Src\UseCases\Domain\Auth\Register;
use App\Src\UseCases\Domain\Ports\OrganizationRepository;
use App\Src\UseCases\Domain\Ports\UserRepository;
use App\Src\UseCases\Domain\User;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Validation\ValidationException;
use Tests\TestCase;

class RegisterUserTest extends TestCase
{
    public function testShouldNotRegister_WhenEmailMissing()
    {
        $email = '';
        $firstname = 'firstname';
        $lastname = 'lastname';
        $password = '';
        $passwordConf = '';
        self::expectException(ValidationException::class);
        app(Register::class)->register($email, $firstname, $lastname, $password, $passwordConf);
    }


    public function testShouldNotRegister_WhenPasswordsDoNotMatch()
    {
        $email = 'anemail@gmail.com';
        $firstname = 'firstname';
        $lastname = 'lastname';
        $password = '123456789';
        $passwordConf = '12345678';
        self::expectException(ValidationException::class);
        app(Register::class)->register($email, $firstname, $lastname, $password, $passwordConf);
    }

    public function testShouldRegisterNewUser()
    {
        $email = 'anemail@gmail.com';
        $firstname = 'firstname';
        $lastname = 'lastname';
        $password = '123456789';
        $passwordConf = '123456789';

        $userId = app(Register::class)->register($email, $firstname, $lastname, $password, $passwordConf);

        $userExpected = new User($userId, $email, $firstname, $lastname);
        $userRegistered = $this->userRepository->getById($userId);

        self::assertEquals($userExpected, $userRegistered);
    }
}
