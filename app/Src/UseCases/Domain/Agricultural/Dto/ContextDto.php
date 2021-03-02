<?php


namespace App\Src\UseCases\Domain\Agricultural\Dto;


use App\Src\UseCases\Domain\Agricultural\Model\PostalCode;

class ContextDto implements \JsonSerializable
{
    public $postalCode;
    public $department;
    public $characteristics;

    public function __construct(string $postalCode, array $characteristics = [])
    {
        $this->postalCode = $postalCode;
        $this->department = (new PostalCode($postalCode))->department();
        $this->characteristics = $characteristics;
    }

    public function jsonSerialize()
    {
        return [
            'postal_code' => $this->postalCode,
            'department' => $this->department,
            'characteristics' => $this->characteristics
        ];
    }
}
