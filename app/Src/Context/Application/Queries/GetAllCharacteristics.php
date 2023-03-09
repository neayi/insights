<?php


namespace App\Src\Context\Application\Queries;


use App\Src\Context\Domain\Characteristic;
use App\Src\Context\Domain\CharacteristicsRepository;

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
