<?php


namespace App\Src\UseCases\Domain\Context\Queries;


use App\Src\UseCases\Domain\Ports\CharacteristicsRepository;

class SearchCharacteristics
{
    private $characteristicsRepository;

    public function __construct(CharacteristicsRepository $characteristicsRepository)
    {
        $this->characteristicsRepository = $characteristicsRepository;
    }

    public function execute(string $type, string $search):array
    {
        return $this->characteristicsRepository->search($type, $search);
    }
}
