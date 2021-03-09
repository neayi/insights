<?php


namespace Tests\Integration\Context;


use App\Src\UseCases\Domain\Agricultural\Dto\CharacteristicDto;
use App\Src\UseCases\Domain\Agricultural\Dto\ContextDto;
use App\Src\UseCases\Domain\Agricultural\Dto\GetFarmingType;
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

        $contextExpected = new ContextDto('first', 'last',$postalCode = 83220);
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

        $contextExpected = new ContextDto('first', 'last', $postalCode = 97400);
        $contextExpected->department = 974;
        $contextExpected->firstname = 'first';
        $contextExpected->lastname = 'last';
        self::assertEquals($contextExpected, $context);
    }


    /**
     * @test
     */
    public function getUserContextCharacteristics()
    {
        $userId1 = Uuid::uuid4()->toString();
        $this->userRepository->add(new User($userId1, 'email@email.com', 'first', 'last'));

        $this->characteristicRepository->add([
            [
                'uuid' => $uuid = Uuid::uuid4()->toString(),
                'code' => 'code',
                'type' => GetFarmingType::type,
                'page_label' => 'c:label',
                'pretty_page_label' => 'label',
                'icon' => 'public/characteristics/'.$uuid.'.png'
            ]
        ]);

        $this->characteristicRepository->add([
            [
                'uuid' => $uuid2 = Uuid::uuid4()->toString(),
                'code' => 'code',
                'type' => GetFarmingType::typeSystem,
                'page_label' => 'c:label2',
                'pretty_page_label' => 'label2',
                'icon' => 'public/characteristics/'.$uuid2.'.png'
            ]
        ]);

        $this->contextRepository->add(new Context(Uuid::uuid4(), 83400, [$uuid, $uuid2]), $userId1);
        $context = app(ContextQueryByUser::class)->execute($userId1);

        $icon = route('api.icon.serve', ['id' => $uuid]);
        $icon2 = route('api.icon.serve', ['id' => $uuid2]);
        $charDto = new CharacteristicDto($uuid, 'c:label', GetFarmingType::type, $icon, 'label');
        $char2Dto = new CharacteristicDto($uuid2, 'c:label2', GetFarmingType::typeSystem, $icon2, 'label2');
        $contextExpected = new ContextDto('first', 'last', $postalCode = 83400, [$charDto, $char2Dto]);


        self::assertEquals($contextExpected, $context);
    }
}
