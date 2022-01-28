<?php


namespace App\Src\UseCases\Domain\Users;


use App\Exceptions\Domain\UserNotFound;
use App\Src\Insights\Insights\Domain\Ports\UserRepository;
use App\Src\UseCases\Domain\User;

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
