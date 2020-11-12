<?php


namespace Tests\Unit\Users\Dto;


use App\Src\UseCases\Domain\Ports\UserRoleRepository;
use App\Src\UseCases\Domain\Users\Dto\GetUserRole;
use App\Src\UseCases\Domain\Users\Dto\WikiUserRole;
use Tests\TestCase;

class GetUserRoleTest extends TestCase
{
    private $userRoleRepository;

    public function setUp(): void
    {
        parent::setUp();
        $this->userRoleRepository = app(UserRoleRepository::class);
    }

    public function testShouldGetUserType()
    {
        $userRole = new WikiUserRole('farmer');
        $userRoleStudent = new WikiUserRole('student');
        $this->userRoleRepository->add($userRole);
        $this->userRoleRepository->add($userRoleStudent);
        $list = app(GetUserRole::class)->get();
        self::assertEquals(collect([$userRole, $userRoleStudent]), $list);
    }
}
