<?php


namespace App\Src\Users\Application;


use App\Src\Users\Domain\Stats;
use App\Src\Users\Domain\UserRepository;

class EditUserStats
{
    private $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function edit(string $userId, array $stats)
    {
        $this->userRepository->addStats($userId, new Stats($stats));
    }
}
