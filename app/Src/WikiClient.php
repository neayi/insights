<?php

declare(strict_types=1);

namespace App\Src;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;

class WikiClient
{
    private Client $client;
    private string $baseUri;

    public function __construct(string $countryCode)
    {
        $this->baseUri = config(sprintf('wiki.api_uri_%s', strtolower($countryCode))).'?';
        $this->client = new Client();
    }

    public function searchStructures(string $search): array
    {
        $query = sprintf('action=query&list=search&srwhat=text&srsearch=%s&srqiprofile=classic_noboostlinks&srnamespace=3000&format=json', $search);
        $uri = $this->baseUri.$query;
        try {
            $response = $this->client->get($uri);
            return json_decode($response->getBody()->getContents(), true);
        } catch (\Throwable $e){
            return ['results' => []];
        }
    }

    /**
     * @throws GuzzleException
     */
    public function searchPages(int $namespace, array $opt = []): array
    {
        $params = array_merge([
            'action' => 'query',
            'list' => 'allpages',
            'apnamespace' => $namespace,
            'aplimit' => 500,
            'apfilterredir' => 'nonredirects',
            'format' => 'json'
        ], $opt);

        $pagesApiUri = $this->baseUri.http_build_query($params);
        $response = $this->client->get($pagesApiUri);

        return json_decode($response->getBody()->getContents(), true);
    }

    /**
     * @throws GuzzleException
     */
    public function searchCharacteristics(array $opt): array
    {
        $params = array_merge([
            'action' => 'ask',
            'api_version' => 3,
            'format' => 'json'
        ], $opt);

        $uri = $this->baseUri.http_build_query($params);
        $response = $this->client->get($uri);

        return json_decode($response->getBody()->getContents(), true);
    }

    /**
     * @throws GuzzleException
     */
    public function getPictureInfo(string $picture): ?array
    {
        $queryPictures = 'action=query&redirects=true&format=json&prop=imageinfo&iiprop=url&titles=';

        $picturesApiUri = $this->baseUri.$queryPictures.$picture;
        $response = $this->client->get($picturesApiUri);

        return json_decode($response->getBody()->getContents(), true);
    }

    /**
     * @throws GuzzleException
     */
    public function downloadPicture(string $uri): string
    {
        $response = $this->client->get($uri);
        return $response->getBody()->getContents();
    }

    /**
     * @throws GuzzleException
     */
    public function getInfoPage(string $page): array
    {
        $queryPages = 'action=query&redirects=true&prop=info&format=json&titles=';

        $pagesApiUri = $this->baseUri.$queryPages.$page;
        $response = $this->client->get($pagesApiUri);

        return json_decode($response->getBody()->getContents(), true);
    }
}
