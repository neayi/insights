<?php


namespace App\Src\Organizations\Invitation;


use App\Src\Shared\Gateway\FileStorage;
use App\Src\Users\UserRepository;
use Illuminate\Support\Facades\Validator;

class PrepareInvitationUsersInOrganization
{
    private $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function prepare(string $organizationId, array $users, string $filePathUsers = null)
    {
        $usersLoop = $usersToProcess = [];
        $usersLoop = $this->getUsersToProcess($users, $usersLoop, $filePathUsers);
        $errors = $imported = 0;

        foreach($usersLoop as $userToInvite){
            $validator = $this->validateUserData($userToInvite);
            if($validator->fails()){
                $userToInvite['error'] = 'email.error.syntax';
                $usersToProcess['users'][] = $userToInvite;
                $errors++;
                continue;
            }

            list($userToInvite, $errors) = $this->checkIfUserAlreadyIn($organizationId, $userToInvite, $errors);

            $usersToProcess['users'][] = $userToInvite;
            if(!isset($userToInvite["error"])){
                $imported++;
            }
        }
        $usersToProcess['total'] = count($usersToProcess['users']);
        $usersToProcess['imported'] = $imported;
        $usersToProcess['error'] = $errors;
        return $usersToProcess;
    }

    private function valueUnique(array $users, array $usersLoop): array
    {
        foreach ($users as $key => $user) {
            $usersLoop[trim($user)] = ['email' => trim($user)];
        }
        return $usersLoop;
    }

    private function getUsersToProcess(array $users, array $usersLoop, string $filePathUsers = null): array
    {
        if (!empty($users) && $filePathUsers === null) {
            return $this->valueUnique($users, $usersLoop);
        }
        return app(FileStorage::class)->content($filePathUsers);
    }

    private function validateUserData($userToInvite): \Illuminate\Contracts\Validation\Validator
    {
        $rules = [
            'email' => 'email|required|min:2|max:255',
            'firstname' => 'string|max:100',
            'lastname' => 'string|max:100',
        ];
        return Validator::make($userToInvite, $rules);
    }

    private function checkIfUserAlreadyIn(string $organizationId, $userToInvite, int $errors): array
    {
        $user = $this->userRepository->getByEmail($userToInvite['email']);
        if (isset($user) && $user->organizationId() === $organizationId) {
            $userToInvite["error"] = 'already_in';
            $errors++;
        }
        return [$userToInvite, $errors];
    }
}
