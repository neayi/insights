<?php


namespace App\Src\Insights\Users\Application\UseCase;


use App\Src\Insights\Users\Domain\Stats;
use App\Src\UseCases\Domain\Ports\UserRepository;

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
