<?php

namespace App\Src\UseCases\Domain\Context\Dto;

class PractiseVo
{
    private $pageId;
    private $label;
    private $doneAt;
    private $wikiCode;

    public function __construct(int $pageId, string $label, \DateTime $doneAt = null, string $wikiCode)
    {
        $this->pageId = $pageId;
        $this->label = $label;
        $this->doneAt = $doneAt;
        $this->wikiCode = $wikiCode;
    }

    public function doneAt():?\DateTime
    {
        return $this->doneAt;
    }

    public function toArray()
    {
        return [
            'page_id' => $this->pageId,
            'label' => $this->label,
            'lang' => $this->wikiCode
        ];
    }
}
