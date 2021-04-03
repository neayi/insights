<?php


namespace App\Src\UseCases\Domain\Ports;


use App\Src\UseCases\Domain\Agricultural\Model\CanInteract;
use App\Src\UseCases\Domain\Agricultural\Model\Interaction;

interface InteractionRepository
{
    public function save(CanInteract $canInteract, Interaction $interaction);
    public function getByInteractUser(CanInteract $canInteract, int $pageId):?Interaction;
}
