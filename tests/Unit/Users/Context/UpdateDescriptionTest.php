<?php


namespace Tests\Unit\Users\Context;


use App\Src\UseCases\Domain\Agricultural\Model\Context;
use App\Src\UseCases\Domain\Context\UpdateDescription;
use App\Src\UseCases\Domain\User;
use Tests\TestCase;

class UpdateDescriptionTest  extends TestCase
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
    public function updateDescriptionContext()
    {
        $description = 'la description du context';
        $context = new Context('abcd', '83220', []);
        $this->contextRepository->add($context, 'abc');

        app(UpdateDescription::class)->execute($description);

        $contextExpected = new Context('abcd', '83220', [], $description);
        $contextSaved = $this->contextRepository->getByUser('abc');
        self::assertEquals($contextExpected, $contextSaved);
    }
}
