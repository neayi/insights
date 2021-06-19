<?php


namespace Tests\Integration\Repositories;


use App\Src\UseCases\Domain\Context\Model\AnonymousUser;
use App\Src\UseCases\Domain\Context\Model\Interaction;
use App\Src\UseCases\Domain\Context\Model\RegisteredUser;
use App\User;
use Tests\TestCase;

class InteractionRepositoryTest extends TestCase
{
    /**
     * @test
     */
    public function shouldSaveInteractions()
    {
        $pageId = 1;
        $user = new User();
        $user->firstname = 'g';
        $user->lastname = 'g';
        $user->uuid = 'abc';
        $user->email = 'abc@gmail.com';
        $user->save();

        $interaction = new Interaction($pageId, true, true, true, ['start_at' => '2020-03-23']);
        $this->interactionRepository->save(new RegisteredUser('abc'), $interaction);


        self::assertDatabaseHas('interactions', ['page_id' => 1, 'user_id' => $user->id, 'start_done_at' => '2020-03-23']);
    }

    /**
     * @test
     */
    public function shouldUpdateInteractions()
    {
        $pageId = 1;
        $user = new User();
        $user->firstname = 'g';
        $user->lastname = 'g';
        $user->uuid = 'abc';
        $user->email = 'abc@gmail.com';
        $user->save();

        $interaction = new Interaction($pageId, true, true, true, ['start_at' => '2020-03-23']);
        $this->interactionRepository->save(new RegisteredUser('abc'), $interaction);

        $interaction = new Interaction($pageId, false, false, false);
        $this->interactionRepository->save(new RegisteredUser('abc'), $interaction);

        self::assertDatabaseHas('interactions', [
            'page_id' => 1,
            'user_id' => $user->id,
            'start_done_at' => null,
            'applause' => false,
            'follow' => false,
            'done' => false,
        ]);

        self::assertDatabaseMissing('interactions', [
            'page_id' => 1,
            'user_id' => $user->id,
            'start_done_at' => '2020-03-23',
            'applause' => true,
            'follow' => true,
            'done' => true,
        ]);
    }

    /**
     * @test
     */
    public function shouldGetInteractions()
    {
        $pageId = 1;
        $user = new User();
        $user->firstname = 'g';
        $user->lastname = 'g';
        $user->uuid = 'abcde';
        $user->email = 'abc@gmail.com';
        $user->save();

        $interaction = new Interaction($pageId, true, true, true, ['start_at' => '2020-03-23']);
        $this->interactionRepository->save(new AnonymousUser('abc'), $interaction);

        self::assertDatabaseHas('interactions', ['page_id' => 1, 'cookie_user_session_id' => 'abc', 'start_done_at' => '2020-03-23']);

        $interactionSaved = $this->interactionRepository->getByInteractUser(new AnonymousUser('abc'), 1);
        self::assertEquals($interaction, $interactionSaved);
    }

    /**
     * @test
     */
    public function shouldTransferInteractions()
    {
        $pageId = 1;
        $user = new User();
        $user->firstname = 'g';
        $user->lastname = 'g';
        $user->uuid = 'abcde';
        $user->email = 'abc@gmail.com';
        $user->save();

        $interaction = new Interaction($pageId, true, true, true, ['start_at' => '2020-03-23']);
        $this->interactionRepository->save($anonymous = new AnonymousUser('abc'), $interaction);

        $this->interactionRepository->transfer($anonymous, $registeredUser = new RegisteredUser('abcde'));

        $interactionUpdated = $this->interactionRepository->getByInteractUser($registeredUser, 1);
        self::assertEquals($interaction, $interactionUpdated);

        $interactionUpdatedAnonymous = $this->interactionRepository->getByInteractUser($anonymous, 1);
        self::assertNull($interactionUpdatedAnonymous);
    }


    /**
     * @test
     */
    public function shouldGetCountInteractions()
    {
        $pageId = 1;

        $interaction = new Interaction($pageId, true, true, false);
        $this->interactionRepository->save($anonymous = new AnonymousUser('abc'), $interaction);

        $interaction = new Interaction($pageId, false, true, false);
        $this->interactionRepository->save($anonymous = new AnonymousUser('abcd'), $interaction);

        $counts = $this->interactionRepository->getCountInteractionsOnPage($pageId);
        $countExpected = ['follow' => 1, 'applause' => 2, 'done' => 0];
        self::assertEquals($countExpected, $counts);
    }
}
