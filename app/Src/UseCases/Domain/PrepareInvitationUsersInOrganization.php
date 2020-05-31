<?php


namespace App\Src\UseCases\Domain;


use App\Src\UseCases\Domain\Ports\UserRepository;
use Illuminate\Support\Facades\Validator;

class PrepareInvitationUsersInOrganization
{
    private $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function prepare(string $organizationId, array $users)
    {
        $usersToProcess = [];
        if(!empty($users)){
            $users = array_unique($users);
            foreach($users as $key => $user){
                $users[$key] = ['email' => trim($user)];
            }
        }
        foreach($users as $userToInvite){
            $rules = [
                'email' => 'email|required|min:2|max:255',
                'firstname' => 'string|max:100',
                'lastname' => 'string|max:100',
            ];
            $validator = Validator::make($userToInvite, $rules);
            if($validator->fails()){
                continue;
            }

            $user = $this->userRepository->getByEmail($userToInvite['email']);
            if(!isset($user) || $user->organizationId() !== $organizationId) {
                $usersToProcess[] = $userToInvite;
            }
        }
        return $usersToProcess;
    }
}
