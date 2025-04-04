<?php


namespace Tests\Integration\Context;


use App\Src\UseCases\Domain\Context\Dto\CharacteristicDto;
use App\Src\UseCases\Domain\Context\Dto\ContextDto;
use App\Src\UseCases\Domain\Context\Model\Characteristic;
use App\Src\UseCases\Domain\Context\Model\Context;
use App\Src\UseCases\Domain\Context\Queries\GetContextByUser;
use App\Src\UseCases\Domain\User;
use App\Src\UseCases\Infra\Sql\Model\CharacteristicsModel;
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
        $this->contextRepository->add(new Context(Uuid::uuid4(), [], 'desc', null, null, 'FR', '83220', null, null, '83'), $userId1);

        $context = app(GetContextByUser::class)->execute($userId1);

        $contextExpected = new ContextDto('first', 'last', 'FR', $postalCode = '83220',  [], 'desc', '', '', $userId1, '83');
        self::assertEquals($contextExpected, $context);
    }

    /**
     * @test
     */
    public function getUserContextDom()
    {
        $userId1 = Uuid::uuid4()->toString();
        $this->userRepository->add(new User($userId1, 'email@email.com', 'first', 'last'));
        $this->contextRepository->add(new Context(Uuid::uuid4(), [], 'desc', '', '', 'FR', '97400', null, null, '974'), $userId1);

        $context = app(GetContextByUser::class)->execute($userId1);

        $contextExpected = new ContextDto('first', 'last', 'FR', $postalCode = '97400',  [], 'desc', '', '', $userId1, '974');
        self::assertEquals($contextExpected, $context);
    }


    /**
     * @test
     */
    public function getUserContextCharacteristics()
    {
        $userId1 = Uuid::uuid4()->toString();
        $this->userRepository->add(new User($userId1, 'email@email.com', 'first', 'last'));

        $characteristic1 = CharacteristicsModel::factory()->create([
            'type' => Characteristic::FARMING_TYPE,
        ]);

        $characteristic2 = CharacteristicsModel::factory()->create([
            'type' => Characteristic::CROPPING_SYSTEM,
        ]);

        $this->contextRepository->add(new Context(Uuid::uuid4(), [$characteristic1->uuid, $characteristic2->uuid], 'description', null, null, 'FR', '83400', null, null, '83'), $userId1);
        $context = app(GetContextByUser::class)->execute($userId1);

        $icon = route('api.icon.serve', ['id' => $characteristic1->uuid]);
        $icon2 = route('api.icon.serve', ['id' => $characteristic2->uuid]);
        $charDto = new CharacteristicDto($characteristic1->uuid, $characteristic1->page_label, Characteristic::FARMING_TYPE, $icon, $characteristic1->pretty_page_label);
        $char2Dto = new CharacteristicDto($characteristic2->uuid, $characteristic2->page_label, Characteristic::CROPPING_SYSTEM, $icon2, $characteristic2->pretty_page_label);

        $contextExpected = new ContextDto('first', 'last', 'FR', $postalCode = '83400', [$charDto, $char2Dto], 'description', null, null, $userId1, '83');

        self::assertEquals($contextExpected, $context);
    }
}
