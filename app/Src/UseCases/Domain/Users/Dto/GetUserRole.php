<?php


namespace App\Src\UseCases\Domain\Users\Dto;


use App\Src\UseCases\Domain\Ports\UserRoleRepository;

class GetUserRole
{
    private $userRoleRepository;

    public function __construct(UserRoleRepository  $userRoleRepository)
    {
        $this->userRoleRepository = $userRoleRepository;
    }

    public function get()
    {
        return $this->userRoleRepository->all();
    }
}
