<?php


namespace App\Src\Insights\Users\Application\UseCase;


use App\Src\Insights\Insights\Domain\Ports\UserRepository;
use App\Src\Insights\Users\Domain\Stats;

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
