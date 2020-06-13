<?php


namespace Tests\Unit\Users;


use App\Src\UseCases\Domain\Ports\UserRepository;
use App\Src\UseCases\Domain\User;
use App\Src\UseCases\Domain\Users\ListUsers;
use Illuminate\Support\Facades\Artisan;
use Ramsey\Uuid\Uuid;
use Tests\TestCase;

class ListUsersTest extends TestCase
{
    private $userRepository;

    public function setUp(): void
    {
        parent::setUp();
        $this->userRepository = app(UserRepository::class);

        if(config('app.env') === 'testing-ti'){
            Artisan::call('migrate:fresh');
        }
    }

    public function testShouldBeEmptyList()
    {
        $organizationId = Uuid::uuid4();
        $users = app(ListUsers::class)->list($organizationId, $page = 1, $perPage = 2);
        self::assertEmpty($users['list']);
    }

    public function testShouldListUsers()
    {
        $organizationId = Uuid::uuid4();
        $u = new User(Uuid::uuid4(), 'org@gmail.com', $organizationId);
        $this->userRepository->add($u);

        $users = app(ListUsers::class)->list($organizationId, $page = 1, $perPage = 2);
        self::assertContainsOnlyInstancesOf(User::class, $users['list']);
        self::assertCount(1, $users['list']);
    }

    public function testShouldPageOfUsers()
    {
        $organizationId = Uuid::uuid4();
        $u = new User(Uuid::uuid4(), 'org@gmail.com', $organizationId);
        $this->userRepository->add($u);

        $u = new User(Uuid::uuid4(), 'org2@gmail.com', $organizationId);
        $this->userRepository->add($u);

        $u = new User(Uuid::uuid4(), 'org3@gmail.com', $organizationId);
        $this->userRepository->add($u);

        $organizations = app(ListUsers::class)->list($organizationId, $page = 1, $perPage = 2);
        self::assertContainsOnlyInstancesOf(User::class, $organizations['list']);
        self::assertCount(2, $organizations['list']);

        $organizations = app(ListUsers::class)->list($organizationId, $page = 2, $perPage = 2);
        self::assertContainsOnlyInstancesOf(User::class, $organizations['list']);
        self::assertCount(1, $organizations['list']);
    }

    public function testShouldListUsersInOrganizationOnly()
    {
        $organizationId = Uuid::uuid4();
        $u = new User(Uuid::uuid4(), 'org@gmail.com', $organizationId);
        $this->userRepository->add($u);

        $organizationId2 = Uuid::uuid4();
        $u = new User(Uuid::uuid4(), 'org2@gmail.com', $organizationId2);
        $this->userRepository->add($u);

        $organizations = app(ListUsers::class)->list($organizationId, $page = 1, $perPage = 2);
        self::assertContainsOnlyInstancesOf(User::class, $organizations['list']);
        self::assertCount(1, $organizations['list']);
    }
}
