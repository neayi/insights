<?php


namespace App\Src\UseCases\Domain\Context\Dto;


use App\Src\UseCases\Domain\Context\Model\Characteristic;

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
    public $userUuid;
    public $hasDone;

    public function __construct(
        string $firstname,
        string $lastname,
        string $postalCode,
        array $characteristics,
        ?string $description,
        ?string $sector,
        ?string $structure,
        ?string $userUuid = null,
        $hasDone = false,
        string $departmentNumber = null
    )
    {
        $this->firstname = $firstname;
        $this->lastname = $lastname;
        $this->postalCode = $postalCode;
        $this->department = $departmentNumber;
        $this->characteristics = $characteristics;
        foreach($this->characteristics as $characteristic){
            $this->characteristicsByType[$characteristic->type()][] = $characteristic;
        }
        $this->description = $description;
        $this->sector = $sector;
        $this->structure = $structure;
        $this->userUuid = $userUuid;
        $this->userUuid = $userUuid;
        $this->hasDone = $hasDone;
    }

    private function fullname():string
    {
        return $this->firstname.' '.$this->lastname;
    }

    public function toArray()
    {
        return [
            'firstname' => $this->firstname,
            'lastname' => $this->lastname,
            'postal_code' => $this->postalCode,
            'department' => $this->department,
            'fullname' => $this->fullname(),
            'productions' => $this->characteristicsByType[Characteristic::FARMING_TYPE] ?? [],
            'characteristics' => $this->characteristicsByType[Characteristic::CROPPING_SYSTEM] ?? [],
            'characteristicsDepartement' => $this->characteristicsByType[Characteristic::DEPARTMENT] ?? [],
            'description' => $this->description,
            'sector' => $this->sector,
            'structure' => $this->structure,
            'userGuid' => $this->userUuid,
            'hasDone' => $this->hasDone,
        ];
    }

    public function jsonSerialize()
    {
        return $this->toArray();
    }
}
