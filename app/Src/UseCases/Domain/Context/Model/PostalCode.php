<?php

declare(strict_types=1);

namespace App\Src\UseCases\Domain\Context\Model;


class PostalCode
{
    public function __construct(
        private string $postalCode
    ){}

    public function department(): string
    {
        if(substr($this->postalCode, 0, 2) > 96){
            return substr($this->postalCode, 0,3);
        }
        return substr($this->postalCode, 0,2);
    }
}
