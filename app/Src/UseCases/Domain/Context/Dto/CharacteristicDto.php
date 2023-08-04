<?php


declare(strict_types=1);

namespace App\Src\UseCases\Domain\Context\Dto;

class CharacteristicDto implements \JsonSerializable
{
    public $uuid;
    public $label;
    public $prettyLabel;
    public $type;
    public $icon;
    public $opt;
    public $code;

    public function __construct(string $uuid,  string $label, string $type, ?string $icon, $prettyLabel, array $opt = [], string $wiki = 'fr')
    {
        $this->uuid = $uuid;
        $this->label = $label;
        $this->type = $type;
        $this->icon = $icon;
        $this->prettyLabel = str_replace('Catégorie:', '', $prettyLabel);
        $this->opt = $opt;
        $this->wiki = $wiki;
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
            'caption' => $this->prettyLabel,
            'opt' => $this->opt,
            'wiki' => $this->wiki
        ];
    }
}
