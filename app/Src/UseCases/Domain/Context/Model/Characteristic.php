<?php


declare(strict_types=1);

namespace App\Src\UseCases\Domain\Context\Model;


use App\Src\UseCases\Domain\Ports\CharacteristicsRepository;
use App\Src\UseCases\Domain\Shared\Model\HasMemento;
use App\Src\UseCases\Domain\Shared\Model\Memento;

class Characteristic implements HasMemento
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

    public function memento(): Memento
    {
        return new CharacteristicMemento($this->id, $this->type, $this->title, $this->visible, $this->icon, $this->pageId, $this->wiki);
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
