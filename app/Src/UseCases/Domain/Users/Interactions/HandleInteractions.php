<?php


namespace App\Src\UseCases\Domain\Users\Interactions;


use App\Src\UseCases\Domain\Agricultural\Model\AnonymousUser;
use App\Src\UseCases\Domain\Agricultural\Model\CanInteract;
use App\Src\UseCases\Domain\Agricultural\Model\RegisteredUser;
use App\Src\UseCases\Domain\Exceptions\PageNotFound;
use App\Src\UseCases\Domain\Ports\InteractionRepository;
use App\Src\UseCases\Domain\Ports\PageRepository;
use App\Src\UseCases\Domain\Shared\Gateway\AuthGateway;
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
        $this->checkPageExist($pageId);

        $canInteractUser = $this->getInteractUser();
        $interaction = $this->interactionRepository->getByInteractUser($canInteractUser, $pageId);
        if(!isset($interaction)) {
            $canInteractUser->addInteraction($interactions, $pageId, $doneValue);
            return;
        }
        $canInteractUser->updateInteraction($interaction, $interactions, $doneValue);
    }

    private function checkPageExist(string $pageId): void
    {
        $page = $this->pageRepository->get($pageId);
        if (!isset($page)) {
            throw new PageNotFound(PageNotFound::error);
        }
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
