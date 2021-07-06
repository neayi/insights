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
use Illuminate\Database\Eloquent\Model;
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
        $user = new User();
        $user->firstname = 'g';
        $user->lastname = 'g';
        $user->uuid = 'abc';
        $user->email = 'abc@gmail.com';
        $user->save();

        $characteristic1 = new CharacteristicsModel();
        $characteristic1->fill([
            'uuid' => 'abc',
            'main' => true,
            'priority' => 0,
            'icon' => '',
            'page_label' => 'label',
            'pretty_page_label' => 'label pretty',
            'page_id' => 1,
            'type' => 'prod',
            'code' => uniqid(),
        ]);
        $characteristic1->save();

        $pageId = 1;
        $interaction = new Interaction($pageId, true, false, false);
        $this->interactionRepository->save(new RegisteredUser('abc'), $interaction);

        $context = new Context('abcd', '83220', ['abc'], '');
        $this->contextRepository->add($context, 'abc');

        $followers = app(GetFollowersOfPage::class)->execute($pageId);

        $contextDtoExpected = new ContextDto('g', 'g', $postalCode = '83220', [$characteristic1->toDto()], '', '', '');
        self::assertEquals($contextDtoExpected, $followers[0]);
    }

    /**
     * @test
     */
    public function shouldGetDoers()
    {
        $user = new User();
        $user->firstname = 'g';
        $user->lastname = 'g';
        $user->uuid = 'abc';
        $user->email = 'abc@gmail.com';
        $user->save();

        $user2 = new User();
        $user2->firstname = 'g2';
        $user2->lastname = 'g2';
        $user2->uuid = 'abcd';
        $user2->email = 'abcd@gmail.com';
        $user2->save();

        $characteristic1 = new CharacteristicsModel();
        $characteristic1->fill([
            'uuid' => 'abc',
            'main' => true,
            'priority' => 0,
            'icon' => '',
            'page_label' => 'label',
            'pretty_page_label' => 'label pretty',
            'page_id' => 1,
            'type' => 'prod',
            'code' => uniqid(),
        ]);
        $characteristic1->save();

        $pageId = 1;
        $interaction = new Interaction($pageId, true, false, false);
        $this->interactionRepository->save(new RegisteredUser('abc'), $interaction);

        $interaction = new Interaction($pageId, false, false, true);
        $this->interactionRepository->save(new RegisteredUser('abcd'), $interaction);

        $context = new Context('abcd', '83220', ['abc'], '');
        $this->contextRepository->add($context, 'abc');

        $context2 = new Context('abcde', '83220', ['abc'], '');
        $this->contextRepository->add($context2, 'abcd');

        $followers = app(GetFollowersOfPage::class)->execute($pageId, $type = "do");

        $contextDtoExpected = new ContextDto('g2', 'g2', $postalCode = '83220', [$characteristic1->toDto()], '', '', '');
        self::assertEquals($contextDtoExpected, $followers[0]);
    }

    /**
     * @test
     */
    public function shouldGetWithDepartment()
    {
        $user = new User();
        $user->firstname = 'g';
        $user->lastname = 'g';
        $user->uuid = 'abc';
        $user->email = 'abc@gmail.com';
        $user->save();

        $user2 = new User();
        $user2->firstname = 'g2';
        $user2->lastname = 'g2';
        $user2->uuid = 'abcd';
        $user2->email = 'abcd@gmail.com';
        $user2->save();

        $characteristic1 = new CharacteristicsModel();
        $characteristic1->fill([
            'uuid' => 'abc',
            'main' => true,
            'priority' => 0,
            'icon' => '',
            'page_label' => 'label',
            'pretty_page_label' => 'label pretty',
            'page_id' => 1,
            'type' => 'prod',
            'code' => uniqid(),
        ]);
        $characteristic1->save();

        $pageId = 1;
        $interaction = new Interaction($pageId, true, false, false);
        $this->interactionRepository->save(new RegisteredUser('abc'), $interaction);

        $interaction = new Interaction($pageId, true, false, true);
        $this->interactionRepository->save(new RegisteredUser('abcd'), $interaction);

        $context = new Context('abcd', '83220', ['abc'], '');
        $this->contextRepository->add($context, 'abc');

        $context2 = new Context('abcde', '06000', ['abc'], '');
        $this->contextRepository->add($context2, 'abcd');

        $followers = app(GetFollowersOfPage::class)->execute($pageId, 'follow', '06');

        $contextDtoExpected = new ContextDto('g2', 'g2', $postalCode = '06000', [$characteristic1->toDto()], '', '', '');
        self::assertEquals($contextDtoExpected, $followers[0]);
    }

    /**
     * @test
     */
    public function shouldGetWithFarmingType()
    {
        $user = new User();
        $user->firstname = 'g';
        $user->lastname = 'g';
        $user->uuid = 'abc';
        $user->email = 'abc@gmail.com';
        $user->save();

        $user2 = new User();
        $user2->firstname = 'g2';
        $user2->lastname = 'g2';
        $user2->uuid = 'abcd';
        $user2->email = 'abcd@gmail.com';
        $user2->save();

        $characteristic1 = new CharacteristicsModel();
        $characteristic1->fill([
            'uuid' => 'abc',
            'main' => true,
            'priority' => 0,
            'icon' => '',
            'page_label' => 'label',
            'pretty_page_label' => 'label pretty',
            'page_id' => 1,
            'type' => Characteristic::FARMING_TYPE,
            'code' => uniqid(),
        ]);
        $characteristic1->save();

        $characteristic2 = new CharacteristicsModel();
        $characteristic2->fill([
            'uuid' => 'abcd',
            'main' => true,
            'priority' => 0,
            'icon' => '',
            'page_label' => 'label',
            'pretty_page_label' => 'label pretty',
            'page_id' => 1,
            'type' => Characteristic::CROPPING_SYSTEM,
            'code' => uniqid(),
        ]);
        $characteristic2->save();

        $pageId = 1;
        $interaction = new Interaction($pageId, true, false, false);
        $this->interactionRepository->save(new RegisteredUser('abc'), $interaction);

        $interaction = new Interaction($pageId, true, false, true);
        $this->interactionRepository->save(new RegisteredUser('abcd'), $interaction);

        $context = new Context('abcd', '83220', ['abcd'], '');
        $this->contextRepository->add($context, 'abc');

        $context2 = new Context('abcde', '83220', ['abc'], '');
        $this->contextRepository->add($context2, 'abcd');

        $followers = app(GetFollowersOfPage::class)->execute($pageId, 'follow', null, null, 'abcd');

        $contextDtoExpected = new ContextDto('g', 'g', $postalCode = '83220', [$characteristic2->toDto()], '', '', '');
        self::assertEquals($contextDtoExpected, $followers[0]);
        self::assertCount(1, $followers);
    }
}
