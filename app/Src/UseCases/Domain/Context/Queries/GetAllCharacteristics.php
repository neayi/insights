<?php


namespace App\Src\UseCases\Domain\Context\Queries;


use App\Src\UseCases\Domain\Context\Model\Characteristic;
use App\Src\UseCases\Domain\Ports\CharacteristicsRepository;

class GetAllCharacteristics
{
    public function __construct(
        private CharacteristicsRepository $characteristicsRepository
    ){}

    public function get(string $countryCode): array
    {
        $mains = $this->characteristicsRepository->getAllByType(Characteristic::FARMING_TYPE, $countryCode);
        $mainsTs = $this->characteristicsRepository->getAllByType(Characteristic::CROPPING_SYSTEM, $countryCode);

        return [
            Characteristic::FARMING_TYPE => $mains,
            Characteristic::CROPPING_SYSTEM => $mainsTs,
        ];
    }
}
