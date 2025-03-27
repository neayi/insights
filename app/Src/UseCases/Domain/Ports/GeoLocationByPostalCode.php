<?php


namespace App\Src\UseCases\Domain\Ports;

interface GeoLocationByPostalCode
{
    public function getGeolocationByPostalCode(string $country, string $postalCode): array;
}
