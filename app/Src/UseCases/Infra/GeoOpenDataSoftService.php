<?php

declare(strict_types=1);

namespace App\Src\UseCases\Infra;

use GuzzleHttp\Client;
use App\Src\UseCases\Domain\Ports\GeoLocationByPostalCode;
use Exception;

class GeoOpenDataSoftService implements GeoLocationByPostalCode
{
    private string $baseUri = 'https://data.opendatasoft.com/api/explore/v2.1/catalog/datasets/%s';

    /**
     * @return array{latitude: ?float, longitude: ?float, department_number: ?string}
     */
    public function getGeolocationByPostalCode(string $country, string $postalCode): array
    {
        $geolocationInformations = [
            'latitude' => null,
            'longitude' => null,
            'department_number' => null,
        ];
        
        try {
            $returnedContent = $this->callApiToRetrieveGeolocation($country, $postalCode);

            if (
                isset($returnedContent['results']) 
                && !empty($returnedContent['results'])
            ) {
                $geolocationInformations['latitude'] = $returnedContent['results'][0]['latitude'];
                $geolocationInformations['longitude'] = $returnedContent['results'][0]['longitude'];
                $geolocationInformations['department_number'] = $returnedContent['results'][0]['admin_code2'];
            }
        } catch (Exception $e) {
            // TODO: log !
        }

        return $geolocationInformations;
    }

    private function callApiToRetrieveGeolocation(string $country, string $postalCode): array
    {
        $dataset = 'geonames-postal-code@public';

        $uri = sprintf($this->baseUri . '/records', urlencode($dataset));

        $params = [
            'where' => sprintf('country_code="%s" AND postal_code="%s"', $country, $postalCode),
            'order_by' => 'accuracy desc',
            'limit' => 10,
        ];

        $result = (new Client())->request(
            'GET',
            $uri . '?' . http_build_query($params),
            [
                'headers' => [
                    'Accept' => 'application/json',
                    'Authorization' => env('OPENDATASOFT_API_KEY'),
                ],
            ]
        );

        return json_decode($result->getBody()->getContents(), true);
    }
}

