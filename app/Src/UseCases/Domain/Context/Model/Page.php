<?php


namespace App\Src\UseCases\Domain\Context\Model;


use App\Src\UseCases\Domain\Ports\PageRepository;
use Ramsey\Uuid\Uuid;

class Page
{
    const TYPE_CULTURE = 'Culture';

    public function __construct(
        private int $pageId,
        private ?string $title = null,
        private ?string $type = null,
        private ?string $icon = null,
    )
    {
    }

    public function pageId():int
    {
        return $this->pageId;
    }

    public function createCharacteristicAssociated():Characteristic
    {
        $type = $this->type === self::TYPE_CULTURE ? Characteristic::FARMING_TYPE : Characteristic::CROPPING_SYSTEM;
        $characteristic = new Characteristic(
            Uuid::uuid4(),
            $type,
            $this->title,
            false,
            $this->pageId
        );
        $characteristic->create($this->icon);
        return $characteristic;
    }
}
