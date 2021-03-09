<?php


namespace App\Src\UseCases\Domain\Agricultural\Dto;


use App\Src\UseCases\Domain\Agricultural\Model\PostalCode;

class ContextDto implements \JsonSerializable
{
    public $firstname;
    public $lastname;
    public $postalCode;
    public $department;
    public $characteristics;
    public $characteristicsByType;

    public function __construct(string $firstname, string $lastname, string $postalCode, array $characteristics = [])
    {
        $this->firstname = $firstname;
        $this->lastname = $lastname;
        $this->postalCode = $postalCode;
        $this->department = (new PostalCode($postalCode))->department();
        $this->characteristics = $characteristics;
        foreach($this->characteristics as $characteristic){
            $this->characteristicsByType[$characteristic->type()][] = $characteristic;
        }
    }

    public function jsonSerialize()
    {
        return [
            'firstname' => $this->firstname,
            'lastname' => $this->lastname,
            'postal_code' => $this->postalCode,
            'department' => $this->department,
            'productions' => $this->characteristicsByType[GetFarmingType::type] ?? [],
            'characteristics' => $this->characteristicsByType[GetFarmingType::typeSystem] ?? [],
        ];
    }
}
