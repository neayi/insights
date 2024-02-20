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

    public function fill(string $userId, string $role, string $firstname, string $lastname, string $email, string $postcode, array $farmingType = [], array $geo = [])
    {
        $errors = [];
        $user = $this->userRepository->getByEmail($email);
        if(isset($user) && $user->id() !== $userId){
            $errors[] = ['validation.unique'];
        }

        $this->validate($firstname, $lastname, $role, $email, $postcode, $geo, $errors);

        $user = $this->userRepository->getById($userId);
        $user->update($email, $firstname, $lastname, "");
        $user->addRole($role);

        $exploitationId = $this->identityProvider->id();

        $context = new Context(
            $exploitationId,
            $postcode,
            $farmingType,
            null,
            null,
            null,
            !empty($geo['coordinates']) ? array_reverse($geo['coordinates']) : [],
            $geo['country_code'] ?? ''
        );
        $this->contextRepository->add($context, $userId);
    }

    private function validate(string $firstname, string $lastname, string $role, string $email, string $postcode, array $geo, array $errors = []): void
    {
        $rules = [
            'firstname' => 'required',
            'lastname' => 'required',
            'role' => 'required',
            'email' => 'required|email',
            'postal_code' => 'required_if:no_postal_code,0',
            'geo' => 'required_if:no_postal_code,0'
        ];

        $validator = Validator::make([
            'firstname' => $firstname,
            'lastname' => $lastname,
            'role' => $role,
            'postal_code' => $postcode,
            'email' => $email,
            'geo' => $geo
        ], $rules);

        $validator->after(function () use ($validator, $errors) {
            foreach ($errors as $field => $error) {
                $validator->errors()->add($field, $error);
            }
        });

        $validator->validate();
    }
}
