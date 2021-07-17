<?php


namespace Tests\Integration\Interaction;


use App\Src\UseCases\Domain\Context\Dto\ContextDto;
use App\Src\UseCases\Domain\Context\Model\Characteristic;
use App\Src\UseCases\Domain\Context\Model\Context;
use App\Src\UseCases\Domain\Context\Model\Interaction;
use App\Src\UseCases\Domain\Context\Model\RegisteredUser;
use App\Src\UseCases\Domain\Context\Queries\GetFollowersOfPage;
use App\Src\UseCases\Infra\Sql\Model\CharacteristicsModel;
use App\User;
use Tests\TestCase;

class FollowersQueryTest extends TestCase
{
    /**
     * @test
     */
    public function shouldGetEmptyFollowers()
    {
        $followers = app(GetFollowersOfPage::class)->execute(1);
        self::assertEmpty($followers);
    }

    /**
     * @test
     */
    public function shouldGetFollowers()
    {
        $user = User::factory()->create();
        $characteristic1 = CharacteristicsModel::factory()->create();

        $pageId = 1;
        $interaction = new Interaction($pageId, true, false, false);
        $this->interactionRepository->save(new RegisteredUser($user->uuid), $interaction);

        $context = new Context('abcd', '83220', [$characteristic1->uuid], '');
        $this->contextRepository->add($context, $user->uuid);

        $followers = app(GetFollowersOfPage::class)->execute($pageId);

        $contextDtoExpected = new ContextDto($user->firstname, $user->lastname, $postalCode = '83220', [$characteristic1->toDto()], '', '', '', $user->uuid, false);
        self::assertEquals($contextDtoExpected, $followers[0]);
    }

    /**
     * @test
     */
    public function shouldGetDoers()
    {
        $user = User::factory()->create();
        $user2 = User::factory()->create();

        $characteristic1 = CharacteristicsModel::factory()->create();

        $pageId = 1;
        $interaction = new Interaction($pageId, true, false, false);
        $this->interactionRepository->save(new RegisteredUser($user->uuid), $interaction);

        $interaction = new Interaction($pageId, false, false, true);
        $this->interactionRepository->save(new RegisteredUser($user2->uuid), $interaction);

        $context = new Context('abcd', '83220', [$characteristic1->uuid], '');
        $this->contextRepository->add($context, $user->uuid);

        $context2 = new Context('abcde', '83220', [$characteristic1->uuid], '');
        $this->contextRepository->add($context2, $user2->uuid);

        $followers = app(GetFollowersOfPage::class)->execute($pageId, $type = "do");

        $contextDtoExpected = new ContextDto($user2->firstname, $user2->lastname, $postalCode = '83220', [$characteristic1->toDto()], '', '', '', $user2->uuid, true);
        self::assertEquals($contextDtoExpected, $followers[0]);
    }

    /**
     * @test
     */
    public function shouldGetWithDepartment()
    {
        $user = User::factory()->create();
        $user2 = User::factory()->create();

        $characteristic1 = CharacteristicsModel::factory()->create();

        $pageId = 1;
        $interaction = new Interaction($pageId, true, false, false);
        $this->interactionRepository->save(new RegisteredUser($user->uuid), $interaction);

        $interaction = new Interaction($pageId, true, false, true);
        $this->interactionRepository->save(new RegisteredUser($user2->uuid), $interaction);

        $context = new Context('abcd', '83220', [$characteristic1->uuid], '',  null, null, '83');
        $this->contextRepository->add($context, $user->uuid);

        $context2 = new Context('abcde', '06000', [$characteristic1->uuid], '', null, null, '06');
        $this->contextRepository->add($context2, $user2->uuid);

        $followers = app(GetFollowersOfPage::class)->execute($pageId, 'follow', '06');

        $contextDtoExpected = new ContextDto($user2->firstname, $user2->lastname, '06000', [$characteristic1->toDto()], '', '', '', $user2->uuid, true, '06');
        self::assertEquals($contextDtoExpected, $followers[0]);
    }

    /**
     * @test
     */
    public function shouldGetWithFarmingType()
    {
        $user = User::factory()->create();
        $user2 = User::factory()->create();

        $characteristic1 = CharacteristicsModel::factory()->create([
            'type' => Characteristic::FARMING_TYPE
        ]);
        $characteristic2 = CharacteristicsModel::factory()->create([
            'type' => Characteristic::CROPPING_SYSTEM,
        ]);

        $pageId = 1;
        $interaction = new Interaction($pageId, true, false, false);
        $this->interactionRepository->save(new RegisteredUser($user->uuid), $interaction);

        $interaction = new Interaction($pageId, true, false, true);
        $this->interactionRepository->save(new RegisteredUser($user2->uuid), $interaction);

        $context = new Context('abcd', '83220', [$characteristic2->uuid], '');
        $this->contextRepository->add($context, $user->uuid);

        $context2 = new Context('abcde', '83220', [$characteristic1->uuid], '');
        $this->contextRepository->add($context2, $user2->uuid);

        $followers = app(GetFollowersOfPage::class)->execute($pageId, 'follow', null, null, $characteristic2->uuid);

        $contextDtoExpected = new ContextDto($user->firstname, $user->lastname, $postalCode = '83220', [$characteristic2->toDto()], '', '', '', $user->uuid, false);
        self::assertEquals($contextDtoExpected, $followers[0]);
        self::assertCount(1, $followers);
    }
}
