<?php


namespace App\Src\UseCases\Domain\Agricultural\Model;


use App\Events\InteractionOnPage;

trait Interact
{
    public function interaction(array $interactions, int $pageId):Interaction
    {
        $follow = in_array('follow', $interactions);
        $applause = in_array('applause', $interactions);
        $done = in_array('done', $interactions);
        return new Interaction($pageId, $follow, $applause, $done);
    }

    public function addInteraction(array $interactions, int $pageId)
    {
        $interaction = $this->interaction($interactions, $pageId);
        $this->interactionRepository->save($this, $interaction);
        event(new InteractionOnPage($pageId));
    }

    public function updateInteraction(Interaction $interaction, array $newInteractions)
    {
        $interaction->update($newInteractions);
        $this->interactionRepository->save($this, $interaction);
        event(new InteractionOnPage($interaction->pageId()));
    }
}
