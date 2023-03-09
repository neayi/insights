<?php


namespace App\Src\Shared\Gateway;


interface GetDepartmentFromPostalCode
{
    public function execute(string $postalCode);
}
