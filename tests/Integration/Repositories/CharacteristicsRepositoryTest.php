<?php


namespace Tests\Integration\Repositories;


use App\Src\UseCases\Domain\Agricultural\Model\Characteristic;
use Tests\TestCase;

class CharacteristicsRepositoryTest extends TestCase
{
    /**
     * @test
     */
    public function saveCharacteristics()
    {
        $char = new Characteristic('type', 'title', false);
        $this->characteristicRepository->save($char);

        self::assertDatabaseHas('characteristics', [
            'type' => 'type',
            'page_label' => 'title'
        ]);
    }

    /**
     * @test
     */
    public function shouldGetCharacteristic()
    {
        $char = new Characteristic('type', 'title', false);
        $this->characteristicRepository->save($char);

        $characteristicFromDb = $this->characteristicRepository->getBy(['type' => 'type', 'title' => 'title']);
        self::assertEquals($char, $characteristicFromDb);
    }

    /**
     * @test
     */
    public function shouldNotGetCharacteristic()
    {
        $characteristicFromDb = $this->characteristicRepository->getBy(['type' => 'type', 'title' => 'title']);
        self::assertNull($characteristicFromDb);
    }
}
