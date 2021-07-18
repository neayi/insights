<?php


namespace App\Src\UseCases\Domain\System;


interface GetDepartmentFromPostalCode
{
    public function execute(string $postalCode);
}
