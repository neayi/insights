<?php


namespace App\Src\Insights\Insights\Domain\Interactions;


interface CanInteract
{
    public function addInteraction(array $interactions, int $pageId, array $doneValue = []);
    public function updateInteraction(Interaction $interaction, array $newInteractions, array $doneValue = []);
    public function key(): string;
    public function identifier():string;
}