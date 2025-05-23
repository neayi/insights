<?php

declare(strict_types=1);

namespace App\Src;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;

class WikiClient
{
    private Client $client;
    private string $baseUri;

    public function __construct(array $configs)
    {
        $this->baseUri = $configs['wiki_api_url'].'?';
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
        /**
         * BUG MediaWiki API ?
         *
         * En utilisant le genrator allpages, les paramètres sont préfixés avec gap (Generator All Pages)
         * Mais l'utilisation de la props semble nous obliger à utiliser aussi une limitation pilimit (valeur acceptée entre 0 et 50)
         * On utilise donc les 2 paramètres de limites (gaplimit et pilimit), avec la même valeur.
         * @see https://stackoverflow.com/questions/35123436/how-to-get-all-wikipedia-pages-from-category-with-title-and-primary-image
         */

        $params = array_merge([
            'action' => 'query',
            'generator' => 'allpages',
            'gapnamespace' => $namespace,
            'gaplimit' => 50,
            'pilimit' => 50,
            'gapfilterredir' => 'nonredirects',
            'format' => 'json',
            'prop' => 'pageimages',
        ], $opt);

        $pagesApiUri = $this->baseUri.http_build_query($params);

        $response = $this->client->get($pagesApiUri);

        return json_decode($response->getBody()->getContents(), true);
    }

    public function searchPagesById(array $pagesIds): array
    {
        $query = "action=query&redirects=true&prop=pageimages&format=json&pithumbsize=250&pageids=";

        $response = $this->client->get($this->baseUri.$query.implode('|', $pagesIds));
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
    public function getInfoPage(string $page): array
    {
        $queryPages = 'action=query&redirects=true&prop=info&format=json&titles=';

        $pagesApiUri = $this->baseUri.$queryPages.$page;
        $response = $this->client->get($pagesApiUri);

        return json_decode($response->getBody()->getContents(), true);
    }

    public function getPagesAdditionalDetail(string|int $offset = null): array
    {
        $query = "action=ask&format=json&api_version=3&query=[[A un glyph::%2B]]|?A un glyph|?A un type de page";

        $pagesApiUri = $this->baseUri.$query;

        if (!empty($offset)) {
            $pagesApiUri .= '|offset='.$offset;
        }

        $response = $this->client->get($pagesApiUri);
        return json_decode($response->getBody()->getContents(), true);
    }
}
