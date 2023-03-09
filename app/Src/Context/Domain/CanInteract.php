<?php


namespace App\Src\Context\Domain;


interface CanInteract
{
    public function addInteraction(array $interactions, int $pageId, array $doneValue = []);
    public function updateInteraction(Interaction $interaction, array $newInteractions, array $doneValue = []);
    public function key(): string;
    public function identifier():string;
}
