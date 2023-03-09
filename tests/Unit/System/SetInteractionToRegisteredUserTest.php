<?php


namespace Tests\Unit\System;


use App\Src\Context\Domain\AnonymousUser;
use App\Src\Context\Domain\Interaction;
use App\Src\Context\Domain\Page;
use App\Src\Context\Domain\RegisteredUser;
use App\Src\UseCases\Domain\System\SetInteractionToRegisteredUser;
use App\Src\UseCases\Domain\User;
use Tests\TestCase;

class SetInteractionToRegisteredUserTest extends TestCase
{

    /**
     * @test
     */
    public function shouldSetInteractionToRegisteredUser()
    {
        $userId = 'abc';
        $this->pageRepository->save(new Page(1));

        $this->authGateway->setWikiSessionId($wikiSessionId = 'session_id');
        $anonymousUser = new AnonymousUser($wikiSessionId);
        $interaction = new Interaction(1, true, false, false);
        $this->interactionRepository->save($anonymousUser, $interaction);


        $user = new User($userId, 'g@gmail.com', 'g', 'g');
        $this->userRepository->add($user);
        $this->authGateway->log($user);

        app(SetInteractionToRegisteredUser::class)->execute();

        $interactionSaved = $this->interactionRepository->getByInteractUser(new RegisteredUser($userId), 1);
        $interactionExpected = clone $interaction;
        self::assertEquals($interactionExpected, $interactionSaved);

        $oldInteraction = $this->interactionRepository->getByInteractUser($anonymousUser, 1);
        self::assertNull($oldInteraction);

    }

    /**
     * @test
     */
    public function shouldDoNothingWhenUserIsNotLogged()
    {
        $this->pageRepository->save(new Page(1));

        $this->authGateway->setWikiSessionId($wikiSessionId = 'session_id');
        $anonymousUser = new AnonymousUser($wikiSessionId);
        $interaction = new Interaction(1, true, false, false);
        $this->interactionRepository->save($anonymousUser, $interaction);

        $state = app(SetInteractionToRegisteredUser::class)->execute();
        self::assertEquals('nothing_to_do', $state);
    }
}
