<?php

declare(strict_types=1);

namespace App\Src\UseCases\Domain\Users\Profile;

use App\Src\UseCases\Domain\Context\Model\Context;
use App\Src\UseCases\Domain\Ports\ContextRepository;
use App\Src\UseCases\Domain\Ports\IdentityProvider;
use App\Src\UseCases\Domain\Ports\UserRepository;
use Illuminate\Support\Facades\Validator;

readonly class FillWikiUserProfile
{
    public function __construct(
        private UserRepository $userRepository,
        private IdentityProvider $identityProvider,
        private ContextRepository $contextRepository
    ){}

    public function fill(string $userId, string $role, string $firstname, string $lastname, string $email, string $country, string $postalCode, array $farmingType = [])
    {
        $errors = [];
        $user = $this->userRepository->getByEmail($email);
        if(isset($user) && $user->id() !== $userId){
            $errors[] = ['validation.unique'];
        }

        $this->validate($firstname, $lastname, $role, $email, $country, $errors);

        $user = $this->userRepository->getById($userId);
        $user->update($email, $firstname, $lastname, "");
        $user->addRole($role);

        $exploitationId = $this->identityProvider->id();

        $context = new Context(
            $exploitationId,
            $postalCode,
            $farmingType,
            null,
            null,
            null,
            $country
        );
        $this->contextRepository->add($context, $userId);
    }

    private function validate(string $firstname, string $lastname, string $role, string $email, string $country, array $errors = []): void
    {
        $rules = [
            'firstname' => 'required',
            'lastname' => 'required',
            'role' => 'required',
            'email' => 'required|email',
            'country' => 'required',
        ];

        $validator = Validator::make([
            'firstname' => $firstname,
            'lastname' => $lastname,
            'role' => $role,
            'email' => $email,
            'country' => $country,
        ], $rules);

        $validator->after(function () use ($validator, $errors) {
            foreach ($errors as $field => $error) {
                $validator->errors()->add($field, $error);
            }
        });

        $validator->validate();
    }
}
