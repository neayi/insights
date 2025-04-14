<?php


namespace Tests\Adapters\Repositories;


use App\Src\UseCases\Domain\Context\Model\Characteristic;
use App\Src\UseCases\Domain\Ports\CharacteristicsRepository;

class InMemoryCharacteristicRepository implements CharacteristicsRepository
{
    private $characteristics = [];

    public function getByType(string $type, bool $isMain): array
    {
        throw new \Exception('not implemented');
    }

    public function getAllByType(string $type, string $wikiCode): array
    {
        // TODO: Implement getAllByType() method.
    }

    public function save(Characteristic $c)
    {
        $this->characteristics[] = $c;
    }

    public function last():Characteristic
    {
        return last($this->characteristics);
    }

    public function getBy(array $conditions): ?Characteristic
    {
        foreach($this->characteristics as $characteristic){
            if($characteristic->type() === $conditions['type'] && $characteristic->title() === $conditions['title']){
                return $characteristic;
            }
        }
        return null;
    }

    public function getByPageId(int $pageId): Characteristic
    {
        foreach($this->characteristics as $characteristic){
            if($characteristic->pageId() === $pageId){
                return $characteristic;
            }
        }
    }
}
