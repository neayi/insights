<?php


namespace App\Src\UseCases\Domain\Ports;

interface GeoLocationByPostalCode
{
    /**
     * @return array{latitude: ?float, longitude: ?float, department_number: ?string}
     */
    public function getGeolocationByPostalCode(string $country, string $postalCode): array;
}
