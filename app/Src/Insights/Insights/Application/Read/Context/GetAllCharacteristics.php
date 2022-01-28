<?php


namespace App\Src\Insights\Insights\Application\Read\Context;


use App\Src\Insights\Insights\Domain\Context\Characteristic;
use App\Src\Insights\Insights\Domain\Ports\CharacteristicsRepository;

class GetAllCharacteristics
{
    private $characteristicsRepository;

    public function __construct(CharacteristicsRepository $characteristicsRepository)
    {
        $this->characteristicsRepository = $characteristicsRepository;
    }

    public function get()
    {
        $mains = $this->characteristicsRepository->getAllByType(Characteristic::FARMING_TYPE);
        $mainsTs = $this->characteristicsRepository->getAllByType(Characteristic::CROPPING_SYSTEM);

        return [
            Characteristic::FARMING_TYPE => $mains,
            Characteristic::CROPPING_SYSTEM => $mainsTs,
        ];
    }
}
