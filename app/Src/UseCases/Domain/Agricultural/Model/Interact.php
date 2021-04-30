<?php


namespace App\Src\UseCases\Domain\Agricultural\Model;


use App\Events\InteractionOnPage;

trait Interact
{
    public function interaction(array $interactions, int $pageId, array $doneValue = []):Interaction
    {
        $follow = in_array('follow', $interactions);
        $applause = in_array('applause', $interactions);
        $done = in_array('done', $interactions);
        return new Interaction($pageId, $follow, $applause, $done, $doneValue);
    }

    public function addInteraction(array $interactions, int $pageId, array $doneValue = [])
    {
        $interaction = $this->interaction($interactions, $pageId, $doneValue);
        $this->interactionRepository->save($this, $interaction);
        event(new InteractionOnPage($pageId));
    }

    public function updateInteraction(Interaction $interaction, array $newInteractions, array $doneValue = [])
    {
        $interaction->update($newInteractions, $doneValue);
        $this->interactionRepository->save($this, $interaction);
        event(new InteractionOnPage($interaction->pageId()));
    }
}
