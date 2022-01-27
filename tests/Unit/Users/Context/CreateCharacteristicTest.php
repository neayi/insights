<?php


namespace Tests\Unit\Users\Context;


use App\Src\Insights\Insights\Domain\Context\Characteristic;
use App\Src\Insights\Insights\Domain\Context\Context;
use App\Src\UseCases\Domain\Context\UseCases\CreateCharacteristic;
use App\Src\UseCases\Domain\User;
use Tests\TestCase;

class CreateCharacteristicTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();
        $currentUser = new User('abc', 'email@gmail.com', 'f', 'l');
        $this->authGateway->log($currentUser);

        $context = new Context('abc', '83220', ['abcde']);
        $this->contextRepository->add($context, 'abc');
    }

    /**
     * @test
     */
    public function shouldCreateCharacteristic()
    {
        $title = 'charact';
        $type = 'croppingSystem';
        $visible = false;
        app(CreateCharacteristic::class)->execute('abcdef', $title, $type);

        $expectedCharacteristic = new Characteristic('abcdef', $title, $type, $visible);
        $characteristicSaved = $this->characteristicRepository->last();
        self::assertEquals($expectedCharacteristic, $characteristicSaved);
    }

    /**
     * @test
     */
    public function shouldCreateCharacteristicAndAddItToUser()
    {
        $title = 'charact';
        $type = 'croppingSystem';

        app(CreateCharacteristic::class)->execute('abcdef', $title, $type);

        $contextExpected = new Context('abc', '83220', ['abcde', 'abcdef']);
        $contextSaved = $this->contextRepository->getByUser('abc');
        self::assertEquals($contextExpected, $contextSaved);
    }

    /**
     * @test
     */
    public function shouldNotCreateCharacteristicTwice()
    {
        $title = 'charact';
        $type = 'croppingSystem';
        $visible = false;

        $characteristic = new Characteristic('abcdef', $title, $type, $visible);
        $this->characteristicRepository->save($characteristic);

        app(CreateCharacteristic::class)->execute('abcdef', $title, $type);
        self::assertTrue(true);
    }


}
