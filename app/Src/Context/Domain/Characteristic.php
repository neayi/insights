<?php


namespace App\Src\Context\Domain;

class Characteristic
{
    const FARMING_TYPE = 'farming';
    const CROPPING_SYSTEM = 'croppingSystem';
    const DEPARTMENT = 'department';

    private $title;
    private $type;
    private $visible;
    private $id;
    private $pageId;
    private $icon = null;

    public function __construct(string $id, string $type, string $title, bool $visible, int $pageId = null)
    {
        $this->id = $id;
        $this->pageId = $pageId;
        $this->type = $type;
        $this->title = $title;
        $this->visible = $visible;
    }

    public function create(string $icon = null)
    {
        if(isset($icon)){
            copy(storage_path('app/'.$icon), storage_path('app/public/characteristics/'.$this->id.'.png'));
            $this->icon = 'public/characteristics/'.$this->id.'.png';
        }
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'type' => $this->type,
            'title' => $this->title,
            'visible' => $this->visible,
            'icon' => $this->icon,
            'pageId' => $this->pageId,
        ];
    }

    public function id(): string
    {
        return $this->id;
    }

    public function pageId(): ?int
    {
        return $this->pageId;
    }
}
