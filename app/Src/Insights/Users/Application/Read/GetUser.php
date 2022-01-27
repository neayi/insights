<?php

namespace App\Src\Insights\Users\Application\Read;

use App\Exceptions\Domain\UserNotFound;
use App\Src\Insights\Users\Infra\Read\SqlUserRepository;

class GetUser
{
    private $userRepository;

    public function __construct(SqlUserRepository $sqlUserRepository)
    {
        $this->userRepository = $sqlUserRepository;
    }

    public function get(string $userId)
    {
        $user = $this->userRepository->getById($userId);
        if(!isset($user)){
            throw new UserNotFound();
        }
        return $user;
    }
}
