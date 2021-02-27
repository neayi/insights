<?php


namespace App\Src\UseCases\Domain\Agricultural\Dto;


use App\Src\UseCases\Domain\Ports\CharacteristicsRepository;

class GetFarmingType
{
    private $characteristicsRepository;
    const type = 'farming';
    const typeSystem = 'croppingSystem';

    public function __construct(CharacteristicsRepository $characteristicsRepository)
    {
        $this->characteristicsRepository = $characteristicsRepository;
    }

    public function get()
    {
        $mains = $this->characteristicsRepository->getByType(self::type, true);
        $others = $this->characteristicsRepository->getByType(self::type, false);
        return [
            'main' => $mains,
            'others' => $others,
        ];
    }
}
