<?php


namespace App\Src\UseCases\Domain\Invitation;


use App\Src\UseCases\Domain\Ports\UserRepository;

class AttachUserToAnOrganization
{
    private $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function attach(string $userId, string $organizationId)
    {
        $user = $this->userRepository->getById($userId);
        $user->joinsOrganization($organizationId);
    }
}
