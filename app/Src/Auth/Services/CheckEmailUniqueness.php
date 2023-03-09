<?php


namespace App\Src\Auth\Services;


use App\Src\Users\UserRepository;

class CheckEmailUniqueness
{
    private $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function validateEmailUniqueness(string $email, \Illuminate\Contracts\Validation\Validator $validator): void
    {
        $errors = [];
        $user = $this->userRepository->getByEmail($email);
        if (isset($user)) {
            $errors['email'] = __('validation.unique', ['attribute' => 'email']);
            $validator->after(function () use ($validator, $errors) {
                foreach ($errors as $field => $error) {
                    $validator->errors()->add($field, $error);
                }
            });
        }
    }
}
