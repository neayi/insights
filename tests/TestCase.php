<?php

namespace Tests;

use App\Src\UseCases\Domain\Ports\CharacteristicsRepository;
use App\Src\UseCases\Domain\Ports\ContextRepository;
use App\Src\UseCases\Domain\Ports\InteractionRepository;
use App\Src\UseCases\Domain\Ports\InvitationRepository;
use App\Src\UseCases\Domain\Ports\PageRepository;
use App\Src\UseCases\Domain\Ports\UserRepository;
use App\Src\UseCases\Domain\Shared\Gateway\AuthGateway;
use App\Src\UseCases\Domain\Shared\Gateway\FileStorage;
use App\Src\UseCases\Domain\Shared\Gateway\SocialiteGateway;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Mail;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication, DatabaseTransactions;

    protected $contextRepository;
    protected $userRepository;
    protected $invitationRepository;
    protected $authGateway;
    protected $socialiteGateway;
    protected $fileStorage;
    protected $characteristicRepository;
    protected $pageRepository;
    protected $interactionRepository;

    public function setUp(): void
    {
        parent::setUp();
        $this->contextRepository = $this->contextRepository();
        $this->userRepository = $this->userRepository();
        $this->invitationRepository = $this->invitationRepository();
        $this->characteristicRepository = $this->characteristicRepository();
        $this->authGateway = $this->authGateway();
        $this->socialiteGateway = $this->socialiteGateway();
        $this->fileStorage = $this->fileStorage();
        $this->pageRepository = $this->pageRepository();
        $this->interactionRepository = $this->interactionRepository();

        Event::fake();
        Mail::fake();
    }

    private function userRepository():UserRepository
    {
        return app(UserRepository::class);
    }

    private function contextRepository():ContextRepository
    {
        return app(ContextRepository::class);
    }

    private function invitationRepository():InvitationRepository
    {
        return app(InvitationRepository::class);
    }

    private function authGateway():AuthGateway
    {
        return app(AuthGateway::class);
    }

    private function socialiteGateway():SocialiteGateway
    {
        return app(SocialiteGateway::class);
    }

    private function fileStorage():FileStorage
    {
        return app(FileStorage::class);
    }

    private function characteristicRepository():CharacteristicsRepository
    {
        return app(CharacteristicsRepository::class);
    }

    private function pageRepository():PageRepository
    {
        return app(PageRepository::class);
    }

    private function interactionRepository():InteractionRepository
    {
        return app(InteractionRepository::class);
    }
}
