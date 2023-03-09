<?php


namespace App\Src\Context\Application\Dto;


class InteractionDto
{
    public $pageId;
    public $follow;
    public $done;
    public $applause;
    public $doneAt;

    public function __construct(int $pageId, bool $follow, bool $done, bool $applause, string $doneAt = null)
    {
        $this->pageId = $pageId;
        $this->done = $done;
        $this->follow = $follow;
        $this->applause = $applause;
        $this->doneAt = $doneAt;
    }
}
