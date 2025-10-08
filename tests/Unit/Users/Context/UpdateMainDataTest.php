<?php


namespace Tests\Unit\Users\Context;


use App\Src\UseCases\Domain\Context\Model\Context;
use App\Src\UseCases\Domain\Context\UseCases\UpdateMainData;
use App\Src\UseCases\Domain\User;
use Tests\TestCase;

class UpdateMainDataTest  extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();
        $currentUser = new User('abc', 'email@gmail.com', 'f', 'l',null, ['student']);
        $this->authGateway->log($currentUser);

        $this->userRepository->add($currentUser);
    }

    /**
     * @test
     */
    public function updateMainDataContext()
    {
        $context = new Context('abc', [], 'test', null, null, 'FR', '83220');
        $this->contextRepository->add($context, 'abc');

        app(UpdateMainData::class)->execute('sector', 'structure', 'newemail@gmail.com', 'newf', 'newl', 'farmer', 'FR', '83130');

        $contextExpected = new Context(
            'abc',
            [],
            'test',
            'sector',
            'structure',
            'FR',
            '83130',
            34,
            43,
            '83'
        );
        $contextSaved = $this->contextRepository->getByUser('abc');
        self::assertEquals($contextExpected, $contextSaved);

        $userExpected = new User('abc', 'newemail@gmail.com', 'newf', 'newl', null, ['farmer']);
        $userSaved = $this->userRepository->getById('abc');
        self::assertEquals($userExpected, $userSaved);
    }
}
