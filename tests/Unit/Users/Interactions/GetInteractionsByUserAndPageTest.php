<?php


namespace Tests\Unit\Users\Interactions;


use App\Src\UseCases\Domain\Context\Model\AnonymousUser;
use App\Src\UseCases\Domain\Context\Model\Interaction;
use App\Src\UseCases\Domain\Context\Model\Page;
use App\Src\UseCases\Domain\Context\Model\RegisteredUser;
use App\Src\UseCases\Domain\Context\Queries\GetInteractionsByPageAndUser;
use App\Src\UseCases\Domain\User;
use Tests\TestCase;
use PHPUnit\Framework\Attributes\Test;

class GetInteractionsByUserAndPageTest extends TestCase
{
    private $wikiCode = 'fr';

    #[Test]
    public function shouldGetNothing()
    {
        $userId = 'abc';
        $user = new User($userId, 'g@gmail.com', 'g', 'g');
        $this->authGateway->log($user);

        self::assertEmpty(app(GetInteractionsByPageAndUser::class)->execute(1, $this->wikiCode));
    }

    #[Test]
    public function shouldGetInteractionForRegisteredUser()
    {
        $userId = 'abc';
        $this->pageRepository->save(new Page($pageId = 1));

        $registeredUser = new RegisteredUser($userId);
        $user = new User($userId, 'g@gmail.com', 'g', 'g');
        $this->authGateway->log($user);
        $this->interactionRepository->save($registeredUser, $interactionExpected = new Interaction($pageId, true, true,false));

        $interactionRetrieved = app(GetInteractionsByPageAndUser::class)->execute($pageId, $this->wikiCode);

        self::assertEquals($interactionExpected, $interactionRetrieved);
    }

    #[Test]
    public function shouldGetInteractionForAnonymousUser()
    {
        $this->pageRepository->save(new Page($pageId = 1));

        $this->authGateway->setWikiSessionId($sid = 'session_id');
        $anonymousUser = new AnonymousUser($sid);
        $this->interactionRepository->save($anonymousUser, $interactionExpected = new Interaction($pageId, true, true,false));

        $interactionRetrieved = app(GetInteractionsByPageAndUser::class)->execute($pageId, $this->wikiCode);

        self::assertEquals($interactionExpected, $interactionRetrieved);
    }
}
