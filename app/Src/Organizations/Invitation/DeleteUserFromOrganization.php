<?php


namespace App\Src\Organizations\Invitation;


use App\Src\Users\UserRepository;

class DeleteUserFromOrganization
{
    private $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function delete(string $userId)
    {
        $user = $this->userRepository->getById($userId);
        $user->leaveOrganization();

    }
}
