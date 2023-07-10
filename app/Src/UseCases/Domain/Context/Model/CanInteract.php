<?php


namespace App\Src\UseCases\Domain\Context\Model;


interface CanInteract
{
    public function addInteraction(array $interactions, int $pageId, string $countryCode, array $doneValue = []);
    public function updateInteraction(Interaction $interaction, array $newInteractions, array $doneValue = []);
    public function key(): string;
    public function identifier():string;
}
