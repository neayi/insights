<?php


namespace Tests\Unit\Users\Context;


use App\Src\UseCases\Domain\Context\Model\Characteristic;
use App\Src\UseCases\Domain\Context\Model\Context;
use App\Src\UseCases\Domain\Context\Model\Page;
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
        $page1 = new Page(1);
        $this->pageRepository->save($page1);
        $page2 = new Page(2);
        $this->pageRepository->save($page2);

        $charsIds = [1, 2];

        $characteristic = new Characteristic('abc', Characteristic::FARMING_TYPE, 'title', false, 1);
        $this->characteristicRepository->save($characteristic);

        $characteristic2 = new Characteristic('def', Characteristic::FARMING_TYPE, 'title', false, 2);
        $this->characteristicRepository->save($characteristic2);

        app(AddCharacteristicsToContext::class)->execute($charsIds);

        $contextExpected = new Context('abc', '83220', ['abc', 'bcd', 'cdf', 'def']);
        $contextSaved = $this->contextRepository->getByUser('abc');
        self::assertEquals($contextExpected, $contextSaved);
    }
}
