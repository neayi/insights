<?php


namespace App\Src\Users\Application;


use App\Exceptions\Domain\UserNotFound;
use App\Src\Users\Domain\User;
use App\Src\Users\Domain\UserRepository;

class GetUser
{
    private $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function get(string $userId):User
    {
        $user = $this->userRepository->getById($userId);
        if(!isset($user)){
            throw new UserNotFound();
        }
        return $user;
    }
}
