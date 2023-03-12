<?php


namespace App\Src\Users\Application;


use App\Src\Users\Domain\UserRepository;

class DeleteUser
{
    private $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function delete(string $userId)
    {
        $user = $this->userRepository->getById($userId);
        $user->delete();
    }
}
