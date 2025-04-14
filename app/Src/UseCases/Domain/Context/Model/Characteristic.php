<?php


declare(strict_types=1);

namespace App\Src\UseCases\Domain\Context\Model;


use App\Src\UseCases\Domain\Ports\CharacteristicsRepository;

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
    private $wiki = 'fr';

    public function __construct(
        string $id,
        string $type,
        string $title,
        bool $visible,
        int $pageId = null,
        string $wiki = 'fr'
    )
    {
        $this->id = $id;
        $this->pageId = $pageId;
        $this->type = $type;
        $this->title = $title;
        $this->visible = $visible;
        $this->wiki = $wiki;
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
}
