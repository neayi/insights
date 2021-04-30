<?php


namespace Tests\Unit\Users\Context;


use App\Src\UseCases\Domain\User;
use Tests\TestCase;

class UpdateMainDataTest  extends TestCase
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
    public function updateMainDataContext()
    {

    }
}
