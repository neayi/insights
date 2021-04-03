<?php


namespace App\Src\UseCases\Domain\Agricultural\Model;


interface CanInteract
{
    public function addInteraction(array $interactions, int $pageId);
    public function updateInteraction(Interaction $interaction, array $newInteractions);
    public function key(): string;
    public function identifier():string;
}
