<?php


namespace App\Src\UseCases\Domain\Users;


use App\Src\UseCases\Domain\Ports\UserRepository;

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
