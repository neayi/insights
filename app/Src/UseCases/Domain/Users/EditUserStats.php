<?php


namespace App\Src\UseCases\Domain\Users;


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
