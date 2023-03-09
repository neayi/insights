<?php


namespace App\Src\Context\Application\Dto;


use App\Src\Context\Domain\Characteristic;
use App\Src\Shared\Model\Dto;

class ContextDto extends Dto
{
    public $firstname;
    public $lastname;
    public $postalCode;
    public $department;
    public $characteristics;
    public $productions;
    public $characteristicsDepartement;
    public $description;
    public $sector;
    public $structure;
    public $userUuid;
    public $fullname;

    public function __construct(
        string $firstname,
        string $lastname,
        string $postalCode,
        array $characteristics,
        ?string $description,
        ?string $sector,
        ?string $structure,
        ?string $userUuid = '',
        string $departmentNumber = ''
    )
    {
        $this->firstname = $firstname;
        $this->lastname = $lastname;
        $this->postalCode = $postalCode;
        $this->department = $departmentNumber;
        $this->fullname = $this->fullname();
        $characteristicsByType = [];
        foreach($characteristics as $characteristic){
            $characteristicsByType[$characteristic->type()][] = $characteristic;
        }

        $this->characteristicsDepartement = $characteristicsByType[Characteristic::DEPARTMENT] ?? [];
        $this->characteristics = $characteristicsByType[Characteristic::CROPPING_SYSTEM] ?? [];
        $this->productions = $characteristicsByType[Characteristic::FARMING_TYPE] ?? [];

        $this->description = $description;
        $this->sector = $sector;
        $this->structure = $structure;
        $this->userUuid = $userUuid;
    }

    private function fullname():string
    {
        return $this->firstname.' '.$this->lastname;
    }

}
