<?php


namespace App\Src\UseCases\Domain\Agricultural\Model;


use App\Src\UseCases\Domain\Ports\PageRepository;

class Page
{
    private $pageId;
    private $dryState;

    public function __construct(int $pageId, bool $dryState = false)
    {
        $this->pageId = $pageId;
        $this->dryState = $dryState;
    }

    public function pageId():int
    {
        return $this->pageId;
    }

    public function setOnDryState()
    {
        $this->dryState = true;
        app(PageRepository::class)->save($this);
    }
}
