<?php


namespace App\Src\Context\Domain;


use Ramsey\Uuid\Uuid;

class Page
{
    private $pageId;
    private $dryState;
    private $title;
    private $type;
    private $icon;

    const TYPE_CULTURE = 'Culture';

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
    public function icon(): ?string
    {
        return $this->icon;
    }

    public function setOnDryState()
    {
        $this->dryState = true;
        app(PageRepository::class)->save($this);
    }

    public function createCharacteristicAssociated(): Characteristic
    {
        $type = $this->type === self::TYPE_CULTURE ? Characteristic::FARMING_TYPE : Characteristic::CROPPING_SYSTEM;
        return new Characteristic(
            Uuid::uuid4(),
            $type,
            $this->title,
            false,
            $this->pageId
        );
    }

    public function toArray()
    {
        return [
            'dry' => $this->dryState
        ];
    }
}
