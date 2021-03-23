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

        $mainsTs = $this->characteristicsRepository->getByType(self::typeSystem, true);
        $othersTs = $this->characteristicsRepository->getByType(self::typeSystem, false);
        return [
            self::type => [
                'main' => $mains,
                'others' => $others
            ],
            self::typeSystem => [
                'main' => $mainsTs,
                'others' => $othersTs
            ],
        ];
    }
}
