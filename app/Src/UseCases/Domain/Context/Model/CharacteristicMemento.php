<?php

declare(strict_types=1);

namespace App\Src\UseCases\Domain\Context\Model;


use App\Src\UseCases\Domain\Shared\Model\Memento;

class CharacteristicMemento implements Memento
{
    private $id;
    private $type;
    private $title;
    private $visible;
    private $icon;
    private $pageId;

    private $wiki;

    public function __construct(string $id, string $type, string $title, bool $visible, ?string $icon = null, ?int $pageId = null, string $wiki = 'fr')
    {
        $this->id = $id;
        $this->type = $type;
        $this->title = $title;
        $this->visible = $visible;
        $this->icon = $icon;
        $this->pageId = $pageId;
        $this->wiki = $wiki;
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

    public function icon():? string
    {
        return $this->icon;
    }

    public function pageId():? int
    {
        return $this->pageId;
    }

    public function wiki(): string
    {
        return $this->wiki;
    }
}
