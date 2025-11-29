<?php

declare(strict_types=1);

namespace App\Src;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;

/**
 * Client to interact with the Wiki Semantic API
 *
 * @see https://wiki.tripleperformance.fr/wiki/Sp%C3%A9cial:BotPasswords (create a bot password for authentication)
 * @see https://www.mediawiki.org/wiki/Extension:SemanticAPI (Docs for Semantic API)
 * @see https://github.com/neayi/mediawiki-extensions-SemanticAPI/blob/main/test/test.php (repo for MediaWiki Semantic API extension    )
 */
class WikiSemanticApiClient
{
    private Client $client;
    private string $baseUri;

    public function __construct(array $configs)
    {
        $this->baseUri = $configs['wiki_url'].'?';
        $this->client = new Client();

        // TODO: Uses API token to authenticate
    }

    public function postPageLikesAmount(int $pageId, int $likesAmount): array
    {
        // TODO: Login or make sure it's logged in

        // TODO: Request the POST endpoint
        $query = sprintf(
            'action=semanticapi&format=json&method=editEntity&entity=%s&property=Likes&value=%d',
            $pageId,
            $likesAmount
        );
        $uri = $this->baseUri.$query;
        try {
            $response = $this->client->post($uri);
            return json_decode($response->getBody()->getContents(), true);
        } catch (\Throwable $e){
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }
}
