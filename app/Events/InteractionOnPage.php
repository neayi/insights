<?php


namespace App\Events;


class InteractionOnPage
{
    public $pageId;

    public function __construct(int $pageId)
    {
        $this->pageId = $pageId;
    }
}
