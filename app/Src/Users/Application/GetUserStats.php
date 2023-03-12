<?php


namespace App\Src\Users\Application;


use App\Src\Users\Domain\UserRepository;

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
