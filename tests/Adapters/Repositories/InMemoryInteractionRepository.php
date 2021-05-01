<?php


namespace Tests\Adapters\Repositories;


use App\Src\UseCases\Domain\Agricultural\Model\CanInteract;
use App\Src\UseCases\Domain\Agricultural\Model\Interaction;
use App\Src\UseCases\Domain\Ports\InteractionRepository;

class InMemoryInteractionRepository implements InteractionRepository
{
    private $interactions = [];

    public function save(CanInteract $canInteract, Interaction $interaction)
    {
        $this->interactions[$canInteract->key()][$canInteract->identifier()][$interaction->pageId()] = $interaction;
    }

    public function getByInteractUser(CanInteract $canInteract, int $pageId):?Interaction
    {
        return $this->interactions[$canInteract->key()][$canInteract->identifier()][$pageId] ?? null;
    }

    public function transfer(CanInteract $anonymous, CanInteract $registered)
    {
        $interactions = $this->interactions[$anonymous->key()][$anonymous->identifier()];
        foreach($interactions as $pageId => $interaction){
            $this->interactions[$registered->key()][$registered->identifier()][$pageId] = $interaction;
        }
        unset($this->interactions[$anonymous->key()][$anonymous->identifier()]);
    }

    public function getCountInteractionsOnPage(int $pageId): array
    {
        return [];
    }

    public function getDoneByUser(string $userId): array
    {
        // TODO: Implement getDoneByUser() method.
    }

}
