<?php


namespace App\Src\UseCases\Domain\Users\profile;


use App\Src\UseCases\Domain\Ports\UserRepository;

class FillWikiUserProfile
{
    private $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function fill(string $userId, string $role, string $firstname, string $lastname)
    {
        $user = $this->userRepository->getById($userId);
        $user->update($user->email(), $firstname, $lastname, "");
        $user->addRole($role);
    }
}