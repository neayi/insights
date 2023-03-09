<?php


namespace App\Src\Users;


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
