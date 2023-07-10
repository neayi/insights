<?php

declare(strict_types=1);

namespace App\Src\UseCases\Domain\Context\Queries;


use App\Src\UseCases\Domain\Context\Model\AnonymousUser;
use App\Src\UseCases\Domain\Context\Model\CanInteract;
use App\Src\UseCases\Domain\Context\Model\Interaction;
use App\Src\UseCases\Domain\Context\Model\RegisteredUser;
use App\Src\UseCases\Domain\Ports\InteractionRepository;
use App\Src\UseCases\Domain\Shared\Gateway\AuthGateway;

class GetInteractionsByPageAndUser
{
    private $interactionRepository;
    private $authGateway;

    public function __construct(
        InteractionRepository $interactionRepository,
        AuthGateway $authGateway
    )
    {
        $this->interactionRepository = $interactionRepository;
        $this->authGateway = $authGateway;
    }

    public function execute(int $pageId, string $countryCode): ?Interaction
    {
        $canInteractUser = $this->getInteractUser();
        return $this->interactionRepository->getByInteractUser($canInteractUser, $pageId, $countryCode);
    }

    private function getInteractUser():CanInteract
    {
        $currentUser = $this->authGateway->current();
        if (isset($currentUser)) {
            return new RegisteredUser($currentUser->id());
        }
        return new AnonymousUser($this->authGateway->wikiSessionId());
    }
}
