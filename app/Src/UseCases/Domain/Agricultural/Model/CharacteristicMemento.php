<?php


namespace App\Src\UseCases\Domain\Agricultural\Model;


use App\Src\UseCases\Domain\Shared\Model\Memento;

class CharacteristicMemento implements Memento
{
    private $id;
    private $type;
    private $title;
    private $visible;

    public function __construct(string $id, string $type, string $title, bool $visible)
    {
        $this->id = $id;
        $this->type = $type;
        $this->title = $title;
        $this->visible = $visible;
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
}
