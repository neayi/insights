<?php


namespace App\Src\UseCases\Domain\Agricultural\Dto;


use App\Src\UseCases\Domain\Agricultural\Model\PostalCode;

class ContextDto implements \JsonSerializable
{
    public $postalCode;
    public $department;

    public function __construct(string $postalCode)
    {
        $this->postalCode = $postalCode;
        $this->department = (new PostalCode($postalCode))->department();
    }

    public function jsonSerialize()
    {
        return [
            'postal_code' => $this->postalCode,
            'department' => $this->department,
        ];
    }
}
