<?php


namespace Tests\Integration\Interaction;


use App\Src\UseCases\Domain\Context\Dto\ContextDto;
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
}