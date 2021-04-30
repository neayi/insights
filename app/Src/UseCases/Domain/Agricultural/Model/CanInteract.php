<?php


namespace App\Src\UseCases\Domain\Agricultural\Model;


interface CanInteract
{
    public function addInteraction(array $interactions, int $pageId, array $doneValue = []);
    public function updateInteraction(Interaction $interaction, array $newInteractions, array $doneValue = []);
    public function key(): string;
    public function identifier():string;
}
