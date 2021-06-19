<?php


namespace Tests\Unit\Users\Context;


use App\Src\UseCases\Domain\Context\Model\Context;
use App\Src\UseCases\Domain\Context\UseCases\AddCharacteristicsToContext;
use App\Src\UseCases\Domain\User;
use Tests\TestCase;

class AddCharacteristicTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();
        $currentUser = new User('abc', 'email@gmail.com', 'f', 'l');
        $this->authGateway->log($currentUser);

        $context = new Context('abc', '83220', ['abc', 'bcd', 'cdf']);
        $this->contextRepository->add($context, 'abc');
    }

    public function testShouldAddCharacteristicsToContext()
    {
        $charsIds = ['abc', 'def'];

        app(AddCharacteristicsToContext::class)->execute($charsIds);

        $contextExpected = new Context('abc', '83220', ['abc', 'bcd', 'cdf', 'def']);
        $contextSaved = $this->contextRepository->getByUser('abc');
        self::assertEquals($contextExpected, $contextSaved);
    }
}
