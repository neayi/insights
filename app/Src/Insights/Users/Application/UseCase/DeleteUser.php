<?php


namespace App\Src\Insights\Users\Application\UseCase;


use App\Exceptions\Domain\UserNotFound;
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
        if(!isset($user)){
            throw new UserNotFound();
        }
        $user->delete();
    }
}
