<?php


namespace Tests\Integration\Repositories;


use App\Src\UseCases\Domain\Context\Model\Characteristic;
use Tests\TestCase;
use PHPUnit\Framework\Attributes\Test;

class CharacteristicsRepositoryTest extends TestCase
{
    #[Test]
    public function saveCharacteristics()
    {
        $char = new Characteristic('abc', 'type', 'title', false);
        $this->characteristicRepository->save($char);

        self::assertDatabaseHas('characteristics', [
            'type' => 'type',
            'page_label' => 'title'
        ]);
    }

    #[Test]
    public function shouldGetCharacteristic()
    {
        $char = new Characteristic('abc', 'type', 'title', false);
        $this->characteristicRepository->save($char);

        $characteristicFromDb = $this->characteristicRepository->getBy(['type' => 'type', 'title' => 'title']);
        self::assertEquals($char, $characteristicFromDb);
    }

    #[Test]
    public function shouldNotGetCharacteristic()
    {
        $characteristicFromDb = $this->characteristicRepository->getBy(['type' => 'type', 'title' => 'title']);
        self::assertNull($characteristicFromDb);
    }
}
