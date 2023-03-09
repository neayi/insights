<?php


namespace App\Src\Users;


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
