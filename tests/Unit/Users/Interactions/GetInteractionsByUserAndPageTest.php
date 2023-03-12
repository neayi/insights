<?php


namespace Tests\Unit\Users\Interactions;


use App\Src\Context\Application\Queries\GetInteractionsByPageAndUser;
use App\Src\Context\Domain\AnonymousUser;
use App\Src\Context\Domain\Interaction;
use App\Src\Context\Domain\Page;
use App\Src\Context\Domain\RegisteredUser;
use App\Src\Users\Domain\User;
use Tests\TestCase;

class GetInteractionsByUserAndPageTest extends TestCase
{
    /**
     * @test
     */
    public function shouldGetNothing()
    {
        $userId = 'abc';
        $user = new User($userId, 'g@gmail.com', 'g', 'g');
        $this->authGateway->log($user);

        self::assertEmpty(app(GetInteractionsByPageAndUser::class)->execute(1));
    }

    /**
     * @test
     */
    public function shouldGetInteractionForRegisteredUser()
    {
        $userId = 'abc';
        $this->pageRepository->save(new Page($pageId = 1));

        $registeredUser = new RegisteredUser($userId);
        $user = new User($userId, 'g@gmail.com', 'g', 'g');
        $this->authGateway->log($user);
        $this->interactionRepository->save($registeredUser, $interactionExpected = new Interaction($pageId, true, true,false));

        $interactionRetrieved = app(GetInteractionsByPageAndUser::class)->execute($pageId);

        self::assertEquals($interactionExpected, $interactionRetrieved);
    }

    /**
     * @test
     */
    public function shouldGetInteractionForAnonymousUser()
    {
        $this->pageRepository->save(new Page($pageId = 1));

        $this->authGateway->setWikiSessionId($sid = 'session_id');
        $anonymousUser = new AnonymousUser($sid);
        $this->interactionRepository->save($anonymousUser, $interactionExpected = new Interaction($pageId, true, true,false));

        $interactionRetrieved = app(GetInteractionsByPageAndUser::class)->execute($pageId);

        self::assertEquals($interactionExpected, $interactionRetrieved);
    }
}
