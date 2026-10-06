<?php


namespace Tests\Unit\Users\Interactions;


use App\Events\InteractionOnPage;
use App\Src\UseCases\Domain\Context\Model\AnonymousUser;
use App\Src\UseCases\Domain\Context\Model\Interaction;
use App\Src\UseCases\Domain\Context\Model\Page;
use App\Src\UseCases\Domain\Context\Model\RegisteredUser;
use App\Src\UseCases\Domain\Exceptions\PageNotFound;
use App\Src\UseCases\Domain\User;
use App\Src\UseCases\Domain\Users\Interactions\HandleInteractions;
use Illuminate\Support\Facades\Event;
use Tests\TestCase;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;

class AddInteractionTest extends TestCase
{
    private $wikiCode = 'fr';

    public function setUp(): void
    {
        parent::setUp();
        $this->authGateway->setWikiSessionId('session_id');
    }

    #[Test]
    public function shouldNotAddNotAllowedInteractions()
    {
        $pageId = 1;
        $this->pageRepository->save(new Page($pageId));
        $interaction = ['forbidden_interaction'];

        self::expectException(\Exception::class);
        self::expectExceptionMessage('interaction_not_allowed');
        app(HandleInteractions::class)->execute($pageId, $interaction, $this->wikiCode);
    }


    #[Test]
    #[DataProvider('dataProvider')]
    public function shouldAddInteractionToUser(array $interaction, Interaction $expected)
    {
        $pageId = 1;
        $this->pageRepository->save(new Page($pageId));

        $user = new User($userId = 'abc', 'g@gmail.com', 'g', 'g');
        $this->userRepository->add($user);
        $this->authGateway->log($user);

        app(HandleInteractions::class)->execute($pageId, $interaction, $this->wikiCode);

        $interactionSaved = $this->interactionRepository->getByInteractUser(new RegisteredUser($userId), $pageId, $this->wikiCode);
        self::assertEquals($expected, $interactionSaved);

        Event::assertDispatched(InteractionOnPage::class);
    }

    public static function dataProvider()
    {
        return [
            [['follow'], new Interaction(1, true, false, false, [], 'fr')],
            [['follow', 'done'], new Interaction(1,true, false, true, [], 'fr')],
            [['unfollow'], new Interaction(1,false, false, false, [], 'fr')],
            [['done'], new Interaction(1,false, false, true, [], 'fr')],
            [['undone'], new Interaction(1,false, false, false, [], 'fr')],
            [['applause'], new Interaction(1,false, true, false, [], 'fr')],
            [['unapplause'], new Interaction(1,false, false, false, [], 'fr')],
        ];
    }


    #[Test]
    public function shouldUpdateInteraction()
    {
        $pageId = 1;
        $this->pageRepository->save(new Page($pageId));

        $user = new User($userId = 'abc', 'g@gmail.com', 'g', 'g');
        $this->userRepository->add($user);
        $this->authGateway->log($user);

        $registeredUser = new RegisteredUser($userId);
        $this->interactionRepository->save($registeredUser, new Interaction(1,false, true, false, [], $this->wikiCode));
        $interaction = ['follow', 'done'];
        app(HandleInteractions::class)->execute($pageId, $interaction, $this->wikiCode);

        $interactionSaved = $this->interactionRepository->getByInteractUser($registeredUser, $pageId, $this->wikiCode);
        $expected = new Interaction(1,true, true, true, [], $this->wikiCode);
        self::assertEquals($expected, $interactionSaved);

        Event::assertDispatched(InteractionOnPage::class);
    }

    #[Test]
    public function shouldAddInteractionWithValue()
    {
        $pageId = 1;
        $this->pageRepository->save(new Page($pageId));

        $user = new User($userId = 'abc', 'g@gmail.com', 'g', 'g');
        $this->userRepository->add($user);
        $this->authGateway->log($user);
        $registeredUser = new RegisteredUser($userId);

        $interaction = ['follow', 'done'];
        app(HandleInteractions::class)->execute($pageId, $interaction, $this->wikiCode, ['start_at' => '2020-10-23']);

        $interactionSaved = $this->interactionRepository->getByInteractUser($registeredUser, $pageId, $this->wikiCode);
        $expected = new Interaction(1,true, false, true, $doneValue = ['start_at' => '2020-10-23'], $this->wikiCode);
        self::assertEquals($expected, $interactionSaved);
    }

    #[Test]
    public function shouldAddInteractionToAnonymousUser()
    {
        $pageId = 1;
        $this->pageRepository->save(new Page($pageId));

        $interaction = ['follow', 'done'];
        app(HandleInteractions::class)->execute($pageId, $interaction, $this->wikiCode);

        $interactionSaved = $this->interactionRepository->getByInteractUser(new AnonymousUser('session_id'), $pageId, $this->wikiCode);
        $expected = new Interaction(1,true, false, true, [], $this->wikiCode);
        self::assertEquals($expected, $interactionSaved);

        Event::assertDispatched(InteractionOnPage::class);
    }

    #[Test]
    public function shouldUpdateInteractionToAnonymousUser()
    {
        $pageId = 1;
        $this->pageRepository->save(new Page($pageId));

        $anonymousUser = new AnonymousUser('session_id');
        $this->interactionRepository->save($anonymousUser, new Interaction(1,false, true, false, [], $this->wikiCode));
        $interaction = ['follow', 'done'];
        app(HandleInteractions::class)->execute($pageId, $interaction, $this->wikiCode);

        $interactionSaved = $this->interactionRepository->getByInteractUser($anonymousUser, $pageId, $this->wikiCode);
        $expected = new Interaction(1,true, true, true, [], $this->wikiCode);
        self::assertEquals($expected, $interactionSaved);

        Event::assertDispatched(InteractionOnPage::class);
    }
}
