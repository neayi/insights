<?php


namespace Tests\Integration\Interaction;


use App\Src\Context\Domain\Characteristic;
use App\Src\Context\Domain\Context;
use App\Src\Context\Domain\Interaction;
use App\Src\Context\Domain\RegisteredUser;
use App\Src\Context\Infrastructure\Model\CharacteristicsModel;
use App\Src\UseCases\Domain\Context\Dto\ContextDto;
use App\Src\UseCases\Domain\Context\Dto\FollowerDto;
use App\Src\UseCases\Domain\Context\Dto\InteractionDto;
use App\Src\UseCases\Domain\Context\Dto\UserDto;
use App\Src\UseCases\Domain\Context\Queries\GetFollowersOfPage;
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

        $followerDtoExpected = new FollowerDto(
           new UserDto($user->uuid, $user->firstname, $user->lastname),
           new ContextDto($user->firstname, $user->lastname, $postalCode = '83220', [$characteristic1->toDto()], '', '', '', $user->uuid),
           new InteractionDto($pageId, true, false, false)
        );

        self::assertEquals($followerDtoExpected, $followers[0]);
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

        $followerDtoExpected = new FollowerDto(
            new UserDto($user2->uuid, $user2->firstname, $user2->lastname),
            new ContextDto($user2->firstname, $user2->lastname, $postalCode = '83220', [$characteristic1->toDto()], '', '', '', $user2->uuid),
            new InteractionDto($pageId, false, true, false)
        );
        self::assertEquals($followerDtoExpected, $followers[0]);
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

        $followerDtoExpected = new FollowerDto(
            new UserDto($user2->uuid, $user2->firstname, $user2->lastname),
            new ContextDto($user2->firstname, $user2->lastname, '06000', [$characteristic1->toDto()], '', '', '', $user2->uuid,'06'),
            new InteractionDto($pageId, true, true, false)
        );
        self::assertEquals($followerDtoExpected, $followers[0]);
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

        $followerDtoExpected = new FollowerDto(
            new UserDto($user->uuid, $user->firstname, $user->lastname),
            new ContextDto($user->firstname, $user->lastname, $postalCode = '83220', [$characteristic2->toDto()], '', '', '', $user->uuid),
            new InteractionDto($pageId, true, false, false)
        );

        self::assertEquals($followerDtoExpected, $followers[0]);
        self::assertCount(1, $followers);
    }
}
