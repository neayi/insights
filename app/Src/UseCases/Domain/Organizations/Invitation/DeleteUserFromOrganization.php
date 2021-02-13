<?php


namespace App\Src\UseCases\Domain\Organizations\Invitation;


use App\Src\UseCases\Domain\Ports\UserRepository;

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
