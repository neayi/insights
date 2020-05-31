<?php


namespace App\Src\UseCases\Domain;


use App\Src\UseCases\Domain\Ports\UserRepository;
use App\Src\UseCases\Infra\Gateway\FileStorage;
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
        $usersToProcess = [];
        if(!empty($users) && $filePathUsers === null){
            $users = array_unique($users);
            foreach($users as $key => $user){
                $users[$key] = ['email' => trim($user)];
            }
        }else {
            $users = app(FileStorage::class)->content($filePathUsers);
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
