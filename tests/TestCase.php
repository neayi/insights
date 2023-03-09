<?php

namespace Tests;

use App\Src\Context\Domain\CharacteristicsRepository;
use App\Src\Context\Domain\ContextRepository;
use App\Src\Context\Domain\InteractionRepository;
use App\Src\Context\Domain\PageRepository;
use App\Src\UseCases\Domain\Ports\InvitationRepository;
use App\Src\UseCases\Domain\Ports\OrganizationRepository;
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
    protected $organizationRepository;
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
        $this->organizationRepository = $this->organizationRepository();
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

    private function organizationRepository():OrganizationRepository
    {
        return app(OrganizationRepository::class);
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
