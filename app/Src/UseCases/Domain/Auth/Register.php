<?php


namespace App\Src\UseCases\Domain\Auth;


use App\Src\UseCases\Domain\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Ramsey\Uuid\Uuid;

class Register
{
    public function register(string $email, string $firstname, string $lastname, string $password, string $passwordConf)
    {
        $this->validateData($email, $firstname, $lastname, $password, $passwordConf);
        $userId = Uuid::uuid4();
        $user = new User($userId, $email, $firstname, $lastname);
        $user->create(Hash::make($password));
        return $userId;
    }

    private function validateData(string $email, string $firstname, string $lastname, string $password, string $passwordConf): void
    {
        $rules = [
            'email' => 'string|required|email|min:2|max:255',
            'firstname' => 'string|min:2|max:255|nullable',
            'lastname' => 'string|min:2|max:255|nullable',
            'password' => 'string|required|min:8|max:255|confirmed',
        ];

        $data = [
            'email' => $email,
            'firstname' => $firstname,
            'lastname' => $lastname,
            'password' => $password,
            'password_confirmation' => $passwordConf,
        ];
        $validator = Validator::make($data, $rules);
        $validator->validate();
    }
}
