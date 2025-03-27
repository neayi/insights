<?php


namespace Tests\Adapters;

use App\Src\UseCases\Domain\Ports\GeoLocationByPostalCode;

class InMemoryGeolocationByPostalCode implements GeoLocationByPostalCode
{
    public function getGeolocationByPostalCode(string $country, string $postalCode): array
    {
        $departmentNumber = substr($postalCode, 0, 2);

        return [
            // 'coordinates' => [43, 117],
            'latitude' => 117,
            'longitude' => 43,
            'department_number' => $departmentNumber,
        ];
    }

}
