<?php


namespace Tests\Adapters\Repositories;


use App\Src\Context\Domain\Characteristic;
use App\Src\Context\Domain\CharacteristicsRepository;

class InMemoryCharacteristicRepository implements CharacteristicsRepository
{
    private $characteristics = [];

    public function getByType(string $type, bool $isMain): array
    {
        throw new \Exception('not implemented');
    }

    public function getAllByType(string $type): array
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
            $characteristicArray = $characteristic->toArray();
            if($characteristicArray['type'] === $conditions['type'] && $characteristicArray['title'] === $conditions['title']){
                return $characteristic;
            }
        }
        return null;
    }

    public function getByPageId(int $pageId): Characteristic
    {
        foreach($this->characteristics as $characteristic){
            $characteristicArray = $characteristic->toArray();
            if($characteristicArray['pageId'] === $pageId){
                return $characteristic;
            }
        }
    }
}
