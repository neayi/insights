<?php


namespace Tests\Integration\Repositories;


use App\Src\UseCases\Domain\Context\Model\Context;
use App\Src\UseCases\Domain\User;
use App\Src\UseCases\Infra\Sql\Model\CharacteristicsModel;
use Tests\TestCase;

class ContextRepositoryTest extends TestCase
{
    /**
     * @test
     */
    public function updateContext()
    {
        $characteristic1 = CharacteristicsModel::factory()->create();
        $characteristic2 = CharacteristicsModel::factory()->create();
        $characteristic3 = CharacteristicsModel::factory()->create();


        $user = new User('abc', 'g@gmail.com', 'f', 'l');
        $this->userRepository->add($user);

        $context = new Context('abc', '83220', [$characteristic1->uuid, $characteristic2->uuid, $characteristic3->uuid], '');
        $this->contextRepository->add($context, 'abc');

        $newContext = new Context('abc', '83130', [$characteristic1->uuid, $characteristic2->uuid], 'test', 'sector', 'structure');
        $this->contextRepository->update($newContext, 'abc');

        $contextSaved = $this->contextRepository->getByUser('abc');
        self::assertEquals($newContext, $contextSaved);
    }

    /**
     * @test
     */
    public function shouldGetContextWithEmptyDescription()
    {
        $characteristic = CharacteristicsModel::factory()->create();

        $user = new User('abc', 'g@gmail.com', 'f', 'l');
        $this->userRepository->add($user);

        $contextExpected = new Context('abc', '83220', [$characteristic->uuid], '', 'sector', 'structure', '83', 117, 43);
        $this->contextRepository->add($contextExpected, 'abc');

        $contextSaved = $this->contextRepository->getByUser('abc');
        self::assertEquals($contextExpected, $contextSaved);
    }

    /**
     * @test
     */
    public function shouldAddContext()
    {
        $characteristic1 = CharacteristicsModel::factory()->create();
        $characteristic2 = CharacteristicsModel::factory()->create();
        $characteristic3 = CharacteristicsModel::factory()->create();

        $user = new User('abc', 'g@gmail.com', 'f', 'l');
        $this->userRepository->add($user);

        $contextExpected = new Context('abc', '83220', [$characteristic1->uuid, $characteristic2->uuid, $characteristic3->uuid], '');
        $this->contextRepository->add($contextExpected, 'abc');

        $contextSaved = $this->contextRepository->getByUser('abc');
        self::assertEquals($contextExpected, $contextSaved);
    }

    /**
     * @test
     */
    public function shouldReturnEmptyContext()
    {
        $user = new User('abc', 'g@gmail.com', 'f', 'l');
        $this->userRepository->add($user);

        $contextSaved = $this->contextRepository->getByUser('abc');
        self::assertNull($contextSaved);
    }
}
