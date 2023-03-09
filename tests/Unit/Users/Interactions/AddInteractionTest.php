<?php


namespace Tests\Unit\Users\Interactions;


use App\Events\InteractionOnPage;
use App\Src\Context\Domain\AnonymousUser;
use App\Src\Context\Domain\Interaction;
use App\Src\Context\Domain\Page;
use App\Src\Context\Domain\RegisteredUser;
use App\Src\UseCases\Domain\User;
use App\Src\UseCases\Domain\Users\Interactions\HandleInteractions;
use Illuminate\Support\Facades\Event;
use Tests\TestCase;

class AddInteractionTest extends TestCase
{
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
        app(HandleInteractions::class)->execute($pageId, $interaction);
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

        app(HandleInteractions::class)->execute($pageId, $interaction);

        $interactionSaved = $this->interactionRepository->getByInteractUser(new RegisteredUser($userId), $pageId);
        self::assertEquals($expected, $interactionSaved);

        Event::assertDispatched(InteractionOnPage::class);
    }

    public function dataProvider()
    {
        return [
            [['follow'], new Interaction(1, true, false, false)],
            [['follow', 'done'], new Interaction(1,true, false, true)],
            [['unfollow'], new Interaction(1,false, false, false)],
            [['done'], new Interaction(1,false, false, true)],
            [['undone'], new Interaction(1,false, false, false)],
            [['applause'], new Interaction(1,false, true, false)],
            [['unapplause'], new Interaction(1,false, false, false)],
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
        $this->interactionRepository->save($registeredUser, new Interaction(1,false, true, false));
        $interaction = ['follow', 'done'];
        app(HandleInteractions::class)->execute($pageId, $interaction);

        $interactionSaved = $this->interactionRepository->getByInteractUser($registeredUser, $pageId);
        $expected = new Interaction(1,true, true, true);
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
        app(HandleInteractions::class)->execute($pageId, $interaction, ['start_at' => '2020-10-23']);

        $interactionSaved = $this->interactionRepository->getByInteractUser($registeredUser, $pageId);
        $expected = new Interaction(1,true, false, true, $doneValue = ['start_at' => '2020-10-23']);
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
        app(HandleInteractions::class)->execute($pageId, $interaction);

        $interactionSaved = $this->interactionRepository->getByInteractUser(new AnonymousUser('session_id'), $pageId);
        $expected = new Interaction(1,true, false, true);
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
        $this->interactionRepository->save($anonymousUser, new Interaction(1,false, true, false));
        $interaction = ['follow', 'done'];
        app(HandleInteractions::class)->execute($pageId, $interaction);

        $interactionSaved = $this->interactionRepository->getByInteractUser($anonymousUser, $pageId);
        $expected = new Interaction(1,true, true, true);
        self::assertEquals($expected, $interactionSaved);

        Event::assertDispatched(InteractionOnPage::class);
    }
}
