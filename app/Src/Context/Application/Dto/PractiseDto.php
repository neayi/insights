<?php


namespace App\Src\Context\Application\Dto;


class PractiseDto
{
    private $pageId;
    private $label;
    private $doneAt;

    public function __construct(int $pageId, string $label, \DateTime $doneAt = null)
    {
        $this->pageId = $pageId;
        $this->label = $label;
        $this->doneAt = $doneAt;
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
        ];
    }
}
