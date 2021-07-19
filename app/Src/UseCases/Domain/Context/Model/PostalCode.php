<?php


namespace App\Src\UseCases\Domain\Context\Model;


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
