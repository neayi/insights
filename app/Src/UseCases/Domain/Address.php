<?php


namespace App\Src\UseCases\Domain;


class Address
{
    private $city;
    private $address1;
    private $address2;
    private $pc;

    public function __construct(string $city, string $address1, string $address2, string $pc)
    {
        $this->city = $city;
        $this->address1 = $address1;
        $this->address2 = $address2;
        $this->pc = $pc;
    }

    public function toArray()
    {
        return [
            'city' => $this->city,
            'address1' => $this->address1,
            'address2' => $this->address2,
            'postal_code' => $this->pc,
        ];
    }
}
