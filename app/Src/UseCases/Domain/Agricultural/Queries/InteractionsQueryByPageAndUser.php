<?php


namespace App\Src\UseCases\Domain\Agricultural\Queries;


use App\Src\UseCases\Domain\Agricultural\Model\AnonymousUser;
use App\Src\UseCases\Domain\Agricultural\Model\CanInteract;
use App\Src\UseCases\Domain\Agricultural\Model\Interaction;
use App\Src\UseCases\Domain\Agricultural\Model\RegisteredUser;
use App\Src\UseCases\Domain\Ports\InteractionRepository;
use App\Src\UseCases\Domain\Shared\Gateway\AuthGateway;

class InteractionsQueryByPageAndUser
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
