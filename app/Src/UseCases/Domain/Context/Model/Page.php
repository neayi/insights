<?php


namespace App\Src\UseCases\Domain\Context\Model;


use App\Src\UseCases\Domain\Ports\PageRepository;

class Page
{
    private $pageId;
    private $dryState;
    private $title;
    private $type;
    private $icon;

    public function __construct(
        int $pageId,
        bool $dryState = false,
        string $title = null,
        string $type = null,
        string $icon = null
    )
    {
        $this->pageId = $pageId;
        $this->dryState = $dryState;
        $this->title = $title;
        $this->type = $type;
        $this->icon = $icon;
    }

    public function pageId():int
    {
        return $this->pageId;
    }

    public function title():?string
    {
        return $this->title;
    }

    public function type():?string
    {
        return $this->type;
    }

    public function icon():?string
    {
        return $this->icon;
    }

    public function setOnDryState()
    {
        $this->dryState = true;
        app(PageRepository::class)->save($this);
    }

    public function toArray()
    {
        return [
            'dry' => $this->dryState
        ];
    }
}
