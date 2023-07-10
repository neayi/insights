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
    private $countryCode = 'FR';

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
        app(HandleInteractions::class)->execute($pageId, $interaction, $this->countryCode);
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

        app(HandleInteractions::class)->execute($pageId, $interaction, $this->countryCode);

        $interactionSaved = $this->interactionRepository->getByInteractUser(new RegisteredUser($userId), $pageId, $this->countryCode);
        self::assertEquals($expected, $interactionSaved);

        Event::assertDispatched(InteractionOnPage::class);
    }

    public function dataProvider()
    {
        return [
            [['follow'], new Interaction(1, true, false, false, [], $this->countryCode)],
            [['follow', 'done'], new Interaction(1,true, false, true, [], $this->countryCode)],
            [['unfollow'], new Interaction(1,false, false, false, [], $this->countryCode)],
            [['done'], new Interaction(1,false, false, true, [], $this->countryCode)],
            [['undone'], new Interaction(1,false, false, false, [], $this->countryCode)],
            [['applause'], new Interaction(1,false, true, false, [], $this->countryCode)],
            [['unapplause'], new Interaction(1,false, false, false, [], $this->countryCode)],
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
        $this->interactionRepository->save($registeredUser, new Interaction(1,false, true, false, [], $this->countryCode));
        $interaction = ['follow', 'done'];
        app(HandleInteractions::class)->execute($pageId, $interaction, $this->countryCode);

        $interactionSaved = $this->interactionRepository->getByInteractUser($registeredUser, $pageId, $this->countryCode);
        $expected = new Interaction(1,true, true, true, [], $this->countryCode);
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
        app(HandleInteractions::class)->execute($pageId, $interaction, $this->countryCode, ['start_at' => '2020-10-23']);

        $interactionSaved = $this->interactionRepository->getByInteractUser($registeredUser, $pageId, $this->countryCode);
        $expected = new Interaction(1,true, false, true, $doneValue = ['start_at' => '2020-10-23'], $this->countryCode);
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
        app(HandleInteractions::class)->execute($pageId, $interaction, $this->countryCode);

        $interactionSaved = $this->interactionRepository->getByInteractUser(new AnonymousUser('session_id'), $pageId, $this->countryCode);
        $expected = new Interaction(1,true, false, true, [], $this->countryCode);
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
        $this->interactionRepository->save($anonymousUser, new Interaction(1,false, true, false, [], $this->countryCode));
        $interaction = ['follow', 'done'];
        app(HandleInteractions::class)->execute($pageId, $interaction, $this->countryCode);

        $interactionSaved = $this->interactionRepository->getByInteractUser($anonymousUser, $pageId, $this->countryCode);
        $expected = new Interaction(1,true, true, true, [], $this->countryCode);
        self::assertEquals($expected, $interactionSaved);

        Event::assertDispatched(InteractionOnPage::class);
    }
}
