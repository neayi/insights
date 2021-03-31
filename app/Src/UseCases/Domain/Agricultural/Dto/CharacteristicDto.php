<?php


namespace App\Src\UseCases\Domain\Agricultural\Dto;


class CharacteristicDto implements \JsonSerializable
{
    public $uuid;
    public $label;
    public $prettyLabel;
    public $type;
    public $icon;

    public function __construct(string $uuid,  string $label, string $type, ?string $icon, $prettyLabel)
    {
        $this->uuid = $uuid;
        $this->label = $label;
        $this->type = $type;
        $this->icon = $icon;
        $this->prettyLabel = $prettyLabel;
    }

    public function type():string
    {
        return $this->type;
    }

    public function jsonSerialize()
    {
        return $this->toArray();
    }

    public function toArray()
    {
        return [
            'page' => $this->label,
            'icon' => $this->icon,
            'caption' => $this->prettyLabel
        ];
    }
}
