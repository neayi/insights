<?php


namespace Tests\Integration\Context;


use App\Src\UseCases\Domain\Agricultural\Dto\ContextDto;
use App\Src\UseCases\Domain\Agricultural\Model\Context;
use App\Src\UseCases\Domain\Agricultural\Queries\ContextQueryByUser;
use App\Src\UseCases\Domain\User;
use Ramsey\Uuid\Uuid;
use Tests\TestCase;

class ContextQueryTest extends TestCase
{
    /**
     * @test
     */
    public function getUserContext()
    {
        $userId1 = Uuid::uuid4()->toString();
        $this->userRepository->add(new User($userId1, 'email@email.com', 'first', 'last'));
        $this->contextRepository->add(new Context(Uuid::uuid4(), 83220), $userId1);

        $context = app(ContextQueryByUser::class)->execute($userId1);

        $contextExpected = new ContextDto($postalCode = 83220);
        self::assertEquals($contextExpected, $context);
    }

    /**
     * @test
     */
    public function getUserContextDom()
    {
        $userId1 = Uuid::uuid4()->toString();
        $this->userRepository->add(new User($userId1, 'email@email.com', 'first', 'last'));
        $this->contextRepository->add(new Context(Uuid::uuid4(), 97400), $userId1);

        $context = app(ContextQueryByUser::class)->execute($userId1);

        $contextExpected = new ContextDto($postalCode = 97400);
        $contextExpected->department = 974;
        self::assertEquals($contextExpected, $context);
    }
}
