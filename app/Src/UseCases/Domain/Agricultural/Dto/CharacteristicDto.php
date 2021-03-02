<?php


namespace App\Src\UseCases\Domain\Agricultural\Dto;


class CharacteristicDto implements \JsonSerializable
{
    private $uuid;
    private $label;
    private $type;
    private $icon;

    public function __construct(string $uuid,  string $label, string $type, ?string $icon)
    {
        $this->uuid = $uuid;
        $this->label = $label;
        $this->type = $type;
        $this->icon = $icon;
    }

    public function jsonSerialize()
    {
        return [
            'label' => $this->label,
            'type' => $this->type,
            'icon' => $this->icon
        ];
    }
}
