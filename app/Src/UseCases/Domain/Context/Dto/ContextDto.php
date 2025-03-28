<?php


namespace App\Src\UseCases\Domain\Context\Dto;


use App\Src\UseCases\Domain\Context\Model\Characteristic;
use App\Src\UseCases\Domain\Shared\Model\Dto;

class ContextDto extends Dto
{
    public string $fullname;
    public array $productions;
    public array $characteristicsDepartement;

    public function __construct(
        public string $firstname,
        public string $lastname,
        public ?string $country,
        public ?string $postalCode,
        public array $characteristics,
        public ?string $description,
        public ?string $sector,
        public ?string $structure,
        public ?string $userUuid,
        public ?string $department = null,
        public string $wiki = 'fr'
    )
    {
        $this->fullname = $this->fullname();
        $characteristicsByType = [];
        foreach($characteristics as $characteristic){
            $characteristicsByType[$characteristic->type()][] = $characteristic;
        }

        $this->characteristicsDepartement = $characteristicsByType[Characteristic::DEPARTMENT] ?? [];
        $this->characteristics = $characteristicsByType[Characteristic::CROPPING_SYSTEM] ?? [];
        $this->productions = $characteristicsByType[Characteristic::FARMING_TYPE] ?? [];
    }

    private function fullname():string
    {
        return $this->firstname.' '.$this->lastname;
    }

}
