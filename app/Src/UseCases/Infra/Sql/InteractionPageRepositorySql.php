<?php


namespace App\Src\UseCases\Infra\Sql;


use App\Src\UseCases\Domain\Agricultural\Model\CanInteract;
use App\Src\UseCases\Domain\Agricultural\Model\Interaction;
use App\Src\UseCases\Domain\Ports\InteractionRepository;

class InteractionPageRepositorySql implements InteractionRepository
{
    public function save(CanInteract $canInteract, Interaction $interaction)
    {
        // TODO: Implement save() method.
    }

    public function getByInteractUser(CanInteract $canInteract, int $pageId): ?Interaction
    {
        // TODO: Implement getByInteractUser() method.
    }

}
