<?php


namespace App\Listeners;


use App\Events\InteractionOnPage;
use App\Src\UseCases\Domain\System\SetPageDryState;

class SetPageDryStateListener
{
    private $setPageDryState;

    public function __construct(SetPageDryState $setPageDryState)
    {
        $this->setPageDryState = $setPageDryState;
    }

    public function handle(InteractionOnPage $event)
    {
        $this->setPageDryState->execute($event->pageId);
    }
}
