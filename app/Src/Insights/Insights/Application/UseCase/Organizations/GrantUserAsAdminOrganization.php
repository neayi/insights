<?php


namespace App\Src\Insights\Insights\Application\UseCase\Organizations;

use App\Exceptions\Domain\UserGrantAdminException;
use App\Src\Insights\Insights\Domain\Ports\UserRepository;

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
