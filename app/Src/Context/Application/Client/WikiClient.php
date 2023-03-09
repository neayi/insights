<?php

namespace App\Src\Context\Application\Client;

use GuzzleHttp\Client;

class WikiClient
{
    private $client;
    private $baseUri;
    public function __construct()
    {
        $this->baseUri = config('wiki.api_uri');
        $this->client = new Client();
    }

    public function searchStructures(string $search): array
    {
        $query = sprintf('?action=query&list=search&srwhat=text&srsearch=%s&srqiprofile=classic_noboostlinks&srnamespace=3000&format=json', $search);
        $uri = $this->baseUri.$query;
        try {
            $response = $this->client->get($uri);
            return json_decode($response->getBody()->getContents(), true);
        }catch (\Throwable $e){
            return ['results' => []];
        }
    }
}
