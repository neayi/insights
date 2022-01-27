<?php


namespace Tests\Unit\Users\Interactions;


use App\Src\Insights\Insights\Domain\Interactions\AnonymousUser;
use App\Src\Insights\Insights\Domain\Interactions\Interaction;
use App\Src\Insights\Insights\Domain\Interactions\Page;
use App\Src\Insights\Insights\Domain\Interactions\RegisteredUser;
use App\Src\UseCases\Domain\Context\Queries\GetInteractionsByPageAndUser;
use App\Src\UseCases\Domain\User;
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
