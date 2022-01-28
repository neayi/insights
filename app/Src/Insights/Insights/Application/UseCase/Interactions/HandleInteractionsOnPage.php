<?php


namespace App\Src\Insights\Insights\Application\UseCase\Interactions;

use App\Src\Insights\Insights\Domain\Interactions\AnonymousUser;
use App\Src\Insights\Insights\Domain\Interactions\CanInteract;
use App\Src\Insights\Insights\Domain\Interactions\RegisteredUser;
use App\Src\Insights\Insights\Domain\Ports\InteractionRepository;
use App\Src\UseCases\Domain\Exceptions\PageNotFound;
use App\Src\UseCases\Domain\Shared\Gateway\AuthGateway;
use Exception;

class HandleInteractionsOnPage
{
    private $interactionRepository;
    private $authGateway;

    private $allowedInteractions = [
        'follow', 'unfollow', 'done', 'undone', 'applause', 'unapplause'
    ];

    public function __construct(
        InteractionRepository $interactionRepository,
        AuthGateway $authGateway
    )
    {
        $this->interactionRepository = $interactionRepository;
        $this->authGateway = $authGateway;
    }

    /**
     * @param string $pageId
     * @param array $interactions
     * @param array $doneValue
     * @throws PageNotFound
     * @throws Exception
     */
    public function execute(string $pageId, array $interactions, array $doneValue = []):void
    {
        $this->checkAllowedInteractions($interactions);

        $canInteractUser = $this->getInteractUser();
        $interaction = $this->interactionRepository->getByInteractUser($canInteractUser, $pageId);
        if(!isset($interaction)) {
            $canInteractUser->addInteraction($interactions, $pageId, $doneValue);
            return;
        }
        $canInteractUser->updateInteraction($interaction, $interactions, $doneValue);
    }

    /**
     * @param array $interactions
     * @throws Exception
     */
    private function checkAllowedInteractions(array $interactions): void
    {
        if (empty(array_intersect($interactions, $this->allowedInteractions))) {
            throw new Exception('interaction_not_allowed');
        }
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
