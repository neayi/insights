<?php


namespace App\Src\UseCases\Domain\Context\Queries;


use App\Src\Insights\Insights\Domain\Interactions\AnonymousUser;
use App\Src\Insights\Insights\Domain\Interactions\CanInteract;
use App\Src\Insights\Insights\Domain\Interactions\Interaction;
use App\Src\Insights\Insights\Domain\Interactions\RegisteredUser;
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
