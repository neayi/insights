<?php


namespace Tests\Unit\Users\Context;


use App\Src\Insights\Insights\Domain\Context\Context;
use App\Src\UseCases\Domain\Context\UseCases\UpdateMainData;
use App\Src\UseCases\Domain\User;
use Tests\TestCase;

class UpdateMainDataTest  extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();
        $currentUser = new User('abc', 'email@gmail.com', 'f', 'l',null, null, ['student']);
        $this->authGateway->log($currentUser);

        $this->userRepository->add($currentUser);
    }

    /**
     * @test
     */
    public function updateMainDataContext()
    {
        $context = new Context('abc', '83220', ['abc', 'bcd', 'cdf'], 'test');
        $this->contextRepository->add($context, 'abc');

        app(UpdateMainData::class)->execute('83130', 'sector', 'structure', 'newemail@gmail.com', 'newf', 'newl', 'farmer');

        $coordinates = [43, 117];
        $contextExpected = new Context(
            'abc',
            '83130',
            ['abc', 'bcd', 'cdf'],
            'test',
            'sector',
            'structure',
            83,
            $coordinates
        );
        $contextSaved = $this->contextRepository->getByUser('abc');
        self::assertEquals($contextExpected, $contextSaved);

        $userExpected = new User('abc', 'newemail@gmail.com', 'newf', 'newl', null, null, ['farmer']);
        $userSaved = $this->userRepository->getById('abc');
        self::assertEquals($userExpected, $userSaved);
    }
}
