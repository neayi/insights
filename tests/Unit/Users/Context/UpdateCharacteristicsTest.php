<?php


namespace Tests\Unit\Users\Context;


use App\Src\Context\Application\UpdateCharacteristics;
use App\Src\Context\Domain\Context;
use App\Src\UseCases\Domain\User;
use Tests\TestCase;

class UpdateCharacteristicsTest  extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();
        $currentUser = new User('abc', 'email@gmail.com', 'f', 'l');
        $this->authGateway->log($currentUser);
    }

    /**
     * @test
     */
    public function updateCharacteristics()
    {
        $context = new Context('abc', '83220', ['abc', 'bcd', 'cdf'], '');
        $this->contextRepository->add($context, 'abc');

        app(UpdateCharacteristics::class)->execute(['abc', 'def']);

        $contextExpected = new Context('abc', '83220', ['abc', 'def'], '');
        $contextSaved = $this->contextRepository->getByUser('abc');
        self::assertEquals($contextExpected, $contextSaved);
    }
}
