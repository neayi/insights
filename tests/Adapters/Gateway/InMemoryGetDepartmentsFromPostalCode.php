<?php


namespace Tests\Adapters\Gateway;


use App\Src\Insights\Insights\Domain\Context\PostalCode;
use App\Src\UseCases\Domain\System\GetDepartmentFromPostalCode;

class InMemoryGetDepartmentsFromPostalCode implements GetDepartmentFromPostalCode
{
    public function execute(string $postalCode)
    {
        return [
            'coordinates' => [43, 117],
            'department_number' => (new PostalCode($postalCode))->department(),
        ];
    }

}
