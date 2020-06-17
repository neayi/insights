<?php


namespace App\Src\UseCases\Organizations;


use App\Exceptions\Domain\UserGrantAdminException;
use App\Src\UseCases\Domain\Ports\UserRepository;

class GrantUserAsAdminOrganization
{
    private $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function grant(string $userId, string $organizationId)
    {
        $user = $this->userRepository->getById($userId);
        if(!$user->belongsTo($organizationId)){
            throw new UserGrantAdminException();
        }

        $user->grantAsAdmin();
    }
}
