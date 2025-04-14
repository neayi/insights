<?php


declare(strict_types=1);

namespace App\Src\UseCases\Domain\Context\Model;


use App\Src\UseCases\Domain\Ports\CharacteristicsRepository;

class Characteristic
{
    const FARMING_TYPE = 'farming';
    const CROPPING_SYSTEM = 'croppingSystem';
    const DEPARTMENT = 'department';

    private ?string $icon = null;

    public function __construct(
        private string $id,
        private string $type,
        private string $title,
        private bool $visible,
        private ?int $pageId = null,
        private string $wiki = 'fr',
        private ?string $label = null
    )
    {
    }

    public function create(string $icon = null)
    {
        if(isset($icon)){
            copy(storage_path('app/'.$icon), storage_path('app/public/characteristics/'.$this->id.'.png'));
            $this->icon = 'public/characteristics/'.$this->id.'.png';
        }
        app(CharacteristicsRepository::class)->save($this);
    }

    public function id(): string
    {
        return $this->id;
    }

    public function type(): string
    {
        return $this->type;
    }

    public function title(): string
    {
        return $this->title;
    }

    public function visible(): bool
    {
        return $this->visible;
    }

    public function icon(): ?string
    {
        return $this->icon;
    }

    public function pageId(): ?int
    {
        return $this->pageId;
    }

    public function wiki(): string
    {
        return $this->wiki;
    }

    public function label(): ?string
    {
        return $this->label;
    }
}
