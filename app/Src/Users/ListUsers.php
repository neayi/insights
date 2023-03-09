<?php


namespace App\Src\Users;


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
