<?php


namespace App\Src\UseCases\Domain\Context\Dto;

use Illuminate\Support\Carbon;

class InteractionDto
{
    public $pageId;
    public $follow;
    public $done;
    public $applause;
    public $doneAt;

    public function __construct(int $pageId, bool $follow, bool $done, bool $applause, Carbon $doneAt = null)
    {
        $this->pageId = $pageId;
        $this->done = $done;
        $this->follow = $follow;
        $this->applause = $applause;
        $this->doneAt = $doneAt ? $doneAt->toDateString() : '';
    }
}
