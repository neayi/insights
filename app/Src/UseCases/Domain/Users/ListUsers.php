<?php


namespace App\Src\UseCases\Domain\Users;


use App\Src\UseCases\Domain\Ports\UserRepository;

class ListUsers
{
    private $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function list(string $organizationId, int $page, int $perPage = 10):array
    {
        $users = $this->userRepository->search($organizationId, $page, $perPage);
        return [
            'list' => $users['list'],
            'total' => $users['total']
        ];
    }
}
