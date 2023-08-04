<?php

declare(strict_types=1);

namespace App\Src\UseCases\Domain\Context\Model;


use App\Events\InteractionOnPage;

trait Interact
{
    public function interaction(array $interactions, int $pageId, string $wikiCode, array $doneValue = []):Interaction
    {
        $follow = in_array('follow', $interactions);
        $applause = in_array('applause', $interactions);
        $done = in_array('done', $interactions);
        return new Interaction($pageId, $follow, $applause, $done, $doneValue, $wikiCode);
    }

    public function addInteraction(array $interactions, int $pageId, string $wikiCode, array $doneValue = [])
    {
        $interaction = $this->interaction($interactions, $pageId, $wikiCode, $doneValue);
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
