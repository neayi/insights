<?php


namespace App\Src\Insights\Users\Application\Read;

use App\Src\Insights\Users\Infra\Read\SqlUserRepository;

class ListUsers
{
    private $userRepository;

    public function __construct(SqlUserRepository $sqlUserRepository)
    {
        $this->userRepository = $sqlUserRepository;
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
