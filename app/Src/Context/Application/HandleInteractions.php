<?php


namespace App\Src\Context\Application;


use App\Src\Context\Domain\AnonymousUser;
use App\Src\Context\Domain\CanInteract;
use App\Src\Context\Domain\InteractionRepository;
use App\Src\Context\Domain\PageRepository;
use App\Src\Context\Domain\RegisteredUser;
use App\Src\Shared\Gateway\AuthGateway;
use App\Src\UseCases\Domain\Exceptions\PageNotFound;
use Exception;

class HandleInteractions
{
    private $pageRepository;
    private $interactionRepository;
    private $authGateway;

    private $allowedInteractions = [
        'follow', 'unfollow', 'done', 'undone', 'applause', 'unapplause'
    ];

    public function __construct(
        PageRepository $pageRepository,
        InteractionRepository $interactionRepository,
        AuthGateway $authGateway
    )
    {
        $this->pageRepository = $pageRepository;
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
