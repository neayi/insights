<?php


namespace App\Src\UseCases\Domain\Context\Dto;


use App\Src\UseCases\Domain\Context\Model\Characteristic;
use App\Src\UseCases\Domain\Context\Model\PostalCode;

class ContextDto implements \JsonSerializable
{
    public $firstname;
    public $lastname;
    public $postalCode;
    public $department;
    public $characteristics;
    public $characteristicsByType;
    public $description;
    public $sector;
    public $structure;

    public function __construct(
        string $firstname,
        string $lastname,
        string $postalCode,
        array $characteristics,
        ?string $description,
        ?string $sector,
        ?string $structure
    )
    {
        $this->firstname = $firstname;
        $this->lastname = $lastname;
        $this->postalCode = $postalCode;
        $this->department = (new PostalCode($postalCode))->department();
        $this->characteristics = $characteristics;
        foreach($this->characteristics as $characteristic){
            $this->characteristicsByType[$characteristic->type()][] = $characteristic;
        }
        $this->description = $description;
        $this->sector = $sector;
        $this->structure = $structure;
    }

    private function fullname():string
    {
        return ucfirst($this->firstname).' '.ucfirst($this->lastname);
    }

    public function toArray()
    {
        return [
            'firstname' => $this->firstname,
            'lastname' => $this->lastname,
            'postal_code' => $this->postalCode,
            'department' => $this->department,
            'fullname' => $this->fullname(),
            'productions' => $this->characteristicsByType[GetFarmingType::type] ?? [],
            'characteristics' => $this->characteristicsByType[GetFarmingType::typeSystem] ?? [],
            'characteristicsDepartement' => $this->characteristicsByType[Characteristic::DEPARTMENT] ?? [],
            'description' => $this->description,
            'sector' => $this->sector,
            'structure' => $this->structure,
        ];
    }

    public function jsonSerialize()
    {
        return $this->toArray();
    }
}
