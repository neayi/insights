<?php


namespace App\Src\Users\Profile;


use App\Src\Context\Domain\Context;
use App\Src\Context\Domain\ContextRepository;
use App\Src\Shared\Gateway\GetDepartmentFromPostalCode;
use App\Src\Shared\IdentityProvider;
use App\Src\Users\UserRepository;
use Illuminate\Support\Facades\Validator;

class FillWikiUserProfile
{
    private $userRepository;
    private $contextRepository;
    private $identityProvider;

    public function __construct(
        UserRepository $userRepository,
        IdentityProvider $identityProvider,
        ContextRepository $contextRepository
    )
    {
        $this->userRepository = $userRepository;
        $this->identityProvider = $identityProvider;
        $this->contextRepository = $contextRepository;
    }

    public function fill(string $userId, string $role, string $firstname, string $lastname, string $email, string $postcode, array $farmingType = [])
    {
        $errors = [];
        $user = $this->userRepository->getByEmail($email);
        if(isset($user) && $user->id() !== $userId){
            $errors[] = ['validation.unique'];
        }

        $this->validate($firstname, $lastname, $role, $email, $postcode, $errors);

        $user = $this->userRepository->getById($userId);
        $user->update($email, $firstname, $lastname, "");
        $user->addRole($role);

        $contextId = $this->identityProvider->id();

        $geoData = app(GetDepartmentFromPostalCode::class)->execute($postcode);

        $context = new Context(
            $contextId,
            $postcode,
            $farmingType,
            null,
            null,
            null,
            $geoData['department_number'] ?? null,
            $geoData['coordinates'] ?? []
        );
        $this->contextRepository->add($context, $userId);
    }

    private function validate(string $firstname, string $lastname, string $role, string $email, string $postcode, array $errors = []): void
    {
        $rules = [
            'firstname' => 'required',
            'lastname' => 'required',
            'role' => 'required',
            'email' => 'required|email',
            'postal_code' => ['required', 'regex:/^((0[1-9])|([1-8][0-9])|(9[0-8])|(2A)|(2B))[0-9]{3}$/'],
        ];

        $validator = Validator::make([
            'firstname' => $firstname,
            'lastname' => $lastname,
            'role' => $role,
            'postal_code' => $postcode,
            'email' => $email
        ], $rules);

        $validator->after(function () use ($validator, $errors) {
            foreach ($errors as $field => $error) {
                $validator->errors()->add($field, $error);
            }
        });

        $validator->validate();
    }
}
