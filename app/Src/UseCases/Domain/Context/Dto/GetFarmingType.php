<?php


namespace App\Src\UseCases\Domain\Context\Dto;


use App\Src\UseCases\Domain\Context\Model\Characteristic;
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
        $mains = $this->characteristicsRepository->getByType(Characteristic::FARMING_TYPE, true);
        $others = $this->characteristicsRepository->getByType(Characteristic::FARMING_TYPE, false);

        $mainsTs = $this->characteristicsRepository->getByType(Characteristic::CROPPING_SYSTEM, true);
        $othersTs = $this->characteristicsRepository->getByType(Characteristic::CROPPING_SYSTEM, false);
        return [
            Characteristic::FARMING_TYPE => [
                'main' => $mains,
                'others' => $others
            ],
            Characteristic::CROPPING_SYSTEM => [
                'main' => $mainsTs,
                'others' => $othersTs
            ],
        ];
    }
}
