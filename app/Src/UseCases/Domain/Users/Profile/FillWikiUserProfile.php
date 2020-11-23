<?php


namespace App\Src\UseCases\Domain\Users\Profile;


use App\Src\UseCases\Domain\Agricultural\Model\Exploitation;
use App\Src\UseCases\Domain\Ports\UserRepository;
use Illuminate\Support\Facades\Validator;
use Ramsey\Uuid\Uuid;

class FillWikiUserProfile
{
    private $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function fill(string $userId, string $role, string $firstname, string $lastname, string $postcode, array $farmingType = [])
    {
        $this->validate($firstname, $lastname, $role, $postcode);

        $user = $this->userRepository->getById($userId);
        $user->update($user->email(), $firstname, $lastname, "");
        $user->addRole($role);

        $exploitation = new Exploitation($exploitationId = Uuid::uuid4(), $postcode, $farmingType);
        $exploitation->create($userId);
    }

    private function validate(string $firstname, string $lastname, string $role, string $postcode): void
    {
        $rules = [
            'firstname' => 'required',
            'lastname' => 'required',
            'role' => 'required',
            'postal_code' => ['required', 'regex:/^((0[1-9])|([1-8][0-9])|(9[0-8])|(2A)|(2B))[0-9]{3}$/'],
        ];

        Validator::make([
            'firstname' => $firstname,
            'lastname' => $lastname,
            'role' => $role,
            'postal_code' => $postcode,
        ], $rules)->validate();
    }
}
