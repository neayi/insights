<?php


namespace Tests\Adapters\Repositories;


use App\Src\UseCases\Domain\Ports\CharacteristicsRepository;

class InMemoryCharacteristicRepository implements CharacteristicsRepository
{
    public function getByType(string $type, bool $isMain): array
    {
        throw new \Exception('not implemented');
    }

    public function add(array $cs)
    {
        // do nothing
    }
}
