<?php


namespace App\Src\Context\Application\Queries;


use App\Src\Context\Domain\AnonymousUser;
use App\Src\Context\Domain\CanInteract;
use App\Src\Context\Domain\Interaction;
use App\Src\Context\Domain\InteractionRepository;
use App\Src\Context\Domain\RegisteredUser;
use App\Src\Shared\Gateway\AuthGateway;

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

    public function execute(int $pageId):?Interaction
    {
        $canInteractUser = $this->getInteractUser();
        return $this->interactionRepository->getByInteractUser($canInteractUser, $pageId);
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
