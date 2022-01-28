<?php


namespace App\Src\UseCases\Domain\Users\Profile;


use App\Src\Insights\Insights\Domain\Context\Context;
use App\Src\Insights\Insights\Domain\Ports\UserRepository;
use App\Src\Insights\Insights\Domain\Service\GetDepartmentFromPostalCode;
use Illuminate\Support\Facades\Validator;

class FillWikiUserProfile
{
    private $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
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

        $exploitationId = $userId;

        $geoData = app(GetDepartmentFromPostalCode::class)->execute($postcode);

        $context = new Context(
            $exploitationId,
            $postcode,
            $farmingType,
            null,
            null,
            null,
            $geoData['department_number'] ?? null,
            $geoData['coordinates'] ?? []
        );
        $context->create($userId);
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
