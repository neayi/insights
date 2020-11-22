<?php


namespace App\Src\UseCases\Domain\Users\profile;


use App\Src\UseCases\Domain\Agricultural\Model\Exploitation;
use App\Src\UseCases\Domain\Ports\ExploitationRepository;
use App\Src\UseCases\Domain\Ports\UserRepository;
use Illuminate\Support\Facades\Validator;

class FillWikiUserProfile
{
    private $userRepository;
    private $exploitationRepository;

    public function __construct(
        UserRepository $userRepository,
        ExploitationRepository $exploitationRepository
    )
    {
        $this->userRepository = $userRepository;
        $this->exploitationRepository = $exploitationRepository;
    }

    public function fill(string $userId, string $role, string $firstname, string $lastname, string $postcode, array $farmingType = [])
    {
        $this->validate($firstname, $lastname, $role, $postcode);

        $user = $this->userRepository->getById($userId);
        $user->update($user->email(), $firstname, $lastname, "");
        $user->addRole($role);

        $this->exploitationRepository->add($userId, new Exploitation($postcode, $farmingType));
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
