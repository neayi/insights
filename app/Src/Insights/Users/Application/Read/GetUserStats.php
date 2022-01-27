<?php

namespace App\Src\Insights\Users\Application\Read;



use App\Src\UseCases\Domain\Ports\UserRepository;

class GetUserStats
{
    private $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function get(string $userId)
    {
        return $this->userRepository->getStats($userId);
    }
}
