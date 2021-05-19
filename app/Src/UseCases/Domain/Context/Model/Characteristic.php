<?php


namespace App\Src\UseCases\Domain\Context\Model;


use App\Src\UseCases\Domain\Ports\CharacteristicsRepository;
use App\Src\UseCases\Domain\Shared\Model\HasMemento;
use App\Src\UseCases\Domain\Shared\Model\Memento;

class Characteristic implements HasMemento
{
    private $title;
    private $type;
    private $visible;
    private $id;

    public function __construct(string $id, string $type, string $title, bool $visible)
    {
        $this->id = $id;
        $this->type = $type;
        $this->title = $title;
        $this->visible = $visible;
    }

    public function create()
    {
        app(CharacteristicsRepository::class)->save($this);
    }

    public function memento(): Memento
    {
        return new CharacteristicMemento($this->id, $this->type, $this->title, $this->visible);
    }
}
