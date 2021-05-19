<?php


namespace App\Src\UseCases\Domain\Context\Dto;


use App\Src\UseCases\Domain\Ports\CharacteristicsRepository;

class GetAllCharacteristics
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
        $mains = $this->characteristicsRepository->getAllByType(self::type);
        $mainsTs = $this->characteristicsRepository->getAllByType(self::typeSystem);

        return [
            self::type => $mains,
            self::typeSystem => $mainsTs,
        ];
    }
}
