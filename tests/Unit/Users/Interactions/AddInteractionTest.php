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

class AddInteractionTest extends TestCase
{
    private $wikiCode = 'fr';

    public function setUp(): void
    {
        parent::setUp();
        $this->authGateway->setWikiSessionId('session_id');
    }

    /**
     * @test
     */
    public function shouldNotAddNotAllowedInteractions()
    {
        $pageId = 1;
        $this->pageRepository->save(new Page($pageId));
        $interaction = ['forbidden_interaction'];

        self::expectException(\Exception::class);
        self::expectExceptionMessage('interaction_not_allowed');
        app(HandleInteractions::class)->execute($pageId, $interaction, $this->wikiCode);
    }


    /**
     * @test
     * @dataProvider dataProvider
     */
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

    public function dataProvider()
    {
        return [
            [['follow'], new Interaction(1, true, false, false, [], $this->wikiCode)],
            [['follow', 'done'], new Interaction(1,true, false, true, [], $this->wikiCode)],
            [['unfollow'], new Interaction(1,false, false, false, [], $this->wikiCode)],
            [['done'], new Interaction(1,false, false, true, [], $this->wikiCode)],
            [['undone'], new Interaction(1,false, false, false, [], $this->wikiCode)],
            [['applause'], new Interaction(1,false, true, false, [], $this->wikiCode)],
            [['unapplause'], new Interaction(1,false, false, false, [], $this->wikiCode)],
        ];
    }


    /**
     * @test
     */
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

    /**
     * @test
     */
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

    /**
     * @test
     */
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

    /**
     * @test
     */
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
