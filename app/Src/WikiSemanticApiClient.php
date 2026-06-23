<?php

declare(strict_types=1);

namespace App\Src;

use GuzzleHttp\Client;
use GuzzleHttp\Cookie\CookieJar;

use RuntimeException;

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
    private string $baseApiUri;
    private string $baseRestUri;
    private CookieJar $cookieJar;

    private const WIKI_SESSION_COOKIE_NAME = 'wiki_session';
    private const WIKI_PROPERTY_LIKES = 'Number of likes';

    public function __construct(array $configs)
    {
        $this->baseApiUri = $configs['wiki_api_url'];
        $this->baseRestUri = $configs['wiki_restapi_url'];
        $this->client = new Client(['cookie' => true]);
        $this->cookieJar = new CookieJar();
    }

    public function postPageLikesAmount(string $pageTitle, int $likesAmount): bool
    {
        // Login or make sure it's logged in
        if (
            0 === $this->cookieJar->count() ||
            null === $this->cookieJar->getCookieByName(self::WIKI_SESSION_COOKIE_NAME) ||
            $this->cookieJar->getCookieByName(self::WIKI_SESSION_COOKIE_NAME)->isExpired()
        ) {
            $this->authenticate();
        }

        // Replaces spaces in page title with underscores
        $pageTitle = str_replace(' ', '_', $pageTitle);

        $uri = sprintf('%s/semanticproperty/%s', $this->baseRestUri, urlencode($pageTitle));

        $response = $this->client->put($uri, [
            'cookies' => $this->cookieJar,
            'json' => [
                'property' => self::WIKI_PROPERTY_LIKES,
                'value' => $likesAmount,
            ],
        ]);

        $responseContent = json_decode($response->getBody()->getContents(), true);

        if (!isset($responseContent['result'])) {
            throw new RuntimeException('Invalid response from Wiki Semantic API: ' . $response->getBody()->getContents());
        }

        return 'success' === $responseContent['result'] ?? false;
    }

    private function authenticate(): void
    {
        $loginTokenResponse = $this->client->get(
            $this->baseApiUri.'?action=query&meta=tokens&type=login&format=json',
            ['cookies' => $this->cookieJar]
        );
        $loginTokenData = json_decode($loginTokenResponse->getBody()->getContents(), true);
        $loginToken = $loginTokenData['query']['tokens']['logintoken'] ?? null;

        if (!$loginToken) {
            throw new RuntimeException("Failed to get login token");
        }

        $loginResponse = $this->client->post(
            $this->baseApiUri,
            [
                'form_params' => [
                    'action' => 'login',
                    'lgname' => env('WIKI_BOT_USERNAME'),
                    'lgpassword' => env('WIKI_BOT_PASSWORD'),
                    'lgtoken' => $loginToken,
                    'format' => 'json'
                ],
                'cookies' => $this->cookieJar,
            ]
        );
        $loginData = json_decode($loginResponse->getBody()->getContents(), true);
        $loginSuccess = $loginData['login']['result'] ?? null;

        if ('Success' !== $loginSuccess) {
            $reason = $loginData['login']['reason'] ?? 'Unknown reason';
            throw new RuntimeException("Login failed: " . $reason);
        }
    }
}
