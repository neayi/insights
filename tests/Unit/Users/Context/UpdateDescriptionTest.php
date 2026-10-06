<?php


namespace Tests\Unit\Users\Context;


use App\Src\UseCases\Domain\Context\Model\Context;
use App\Src\UseCases\Domain\Context\UseCases\UpdateDescription;
use App\Src\UseCases\Domain\User;
use Tests\TestCase;
use PHPUnit\Framework\Attributes\Test;

class UpdateDescriptionTest  extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();
        $currentUser = new User('abc', 'email@gmail.com', 'f', 'l');
        $this->authGateway->log($currentUser);
    }

    #[Test]
    public function updateDescriptionContext()
    {
        $description = 'la description du context';
        $context = new Context('abcd', []);
        $this->contextRepository->add($context, 'abc');

        app(UpdateDescription::class)->execute($description);

        $contextExpected = new Context('abcd', [], $description);
        $contextSaved = $this->contextRepository->getByUser('abc');
        self::assertEquals($contextExpected, $contextSaved);
    }
}
