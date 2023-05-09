<?php

declare(strict_types=1);

namespace App;

use GuzzleHttp\Client;

class GeoOpenDataSoftService
{
    private string $baseUri = 'https://data.opendatasoft.com/api/records/1.0/search/?';

    private array $params = [
        'dataset' => 'geonames-postal-code@public',
    ];

    public function getGeolocationByPostalCode(string $postalCode)
    {
        $this->params['q'] = sprintf('postal_code=%s', $postalCode);
        $this->params['rows'] = 100;
        $this->params['sort'] = 'accuracy';
        $result = (new Client())->request(
            'GET',
            $this->baseUri . http_build_query($this->params),
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

