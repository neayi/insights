<?php


namespace App\Src\Context\Domain;


class PostalCode
{
    private $postalCode;

    public function __construct(string $postalCode)
    {
        $this->postalCode = $postalCode;
    }

    public function department():string
    {
        if(substr($this->postalCode, 0, 2) > 96){
            return substr($this->postalCode, 0,3);
        }
        return substr($this->postalCode, 0,2);
    }
}
