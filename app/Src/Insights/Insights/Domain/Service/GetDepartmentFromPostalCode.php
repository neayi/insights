<?php


namespace App\Src\Insights\Insights\Domain\Service;


interface GetDepartmentFromPostalCode
{
    public function execute(string $postalCode);
}
