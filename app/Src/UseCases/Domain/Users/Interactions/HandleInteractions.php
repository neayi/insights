<?php

declare(strict_types=1);

namespace App\Src\UseCases\Domain\Users\Interactions;


use App\Src\UseCases\Domain\Context\Model\AnonymousUser;
use App\Src\UseCases\Domain\Context\Model\CanInteract;
use App\Src\UseCases\Domain\Context\Model\RegisteredUser;
use App\Src\UseCases\Domain\Exceptions\PageNotFound;
use App\Src\UseCases\Domain\Ports\InteractionRepository;
use App\Src\UseCases\Domain\Ports\PageRepository;
use App\Src\UseCases\Domain\Shared\Gateway\AuthGateway;
use Exception;

class HandleInteractions
{
    private array $allowedInteractions = [
        'follow', 'unfollow', 'done', 'undone', 'applause', 'unapplause'
    ];

    public function __construct(
        private PageRepository                 $pageRepository,
        private readonly InteractionRepository $interactionRepository,
        private readonly AuthGateway           $authGateway
    ){}

    /**
     * @param string $pageId
     * @param array $interactions
     * @param array $doneValue
     * @throws PageNotFound
     * @throws Exception
     */
    public function execute(int $pageId, array $interactions, string $countryCode, array $doneValue = []):void
    {
        $this->checkAllowedInteractions($interactions);

        $canInteractUser = $this->getInteractUser();
        $interaction = $this->interactionRepository->getByInteractUser($canInteractUser, $pageId, $countryCode);
        if(!isset($interaction)) {
            $canInteractUser->addInteraction($interactions, $pageId, $countryCode, $doneValue);
            return;
        }
        $canInteractUser->updateInteraction($interaction, $interactions, $doneValue, $countryCode);
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
