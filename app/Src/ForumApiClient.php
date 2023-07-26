<?php

declare(strict_types=1);

namespace App\Src;

use GuzzleHttp\Client;

class ForumApiClient
{
    private Client $client;

    public function __construct(string $baseUri)
    {
        $this->client = new Client(['base_uri' => $baseUri]);
    }

    public function getUserByUsername(string $username): array
    {
        $response = $this->client->get('search.json?q=order:latest @'.$username->discourse_username());
        return json_decode($response->getBody()->getContents(), true);
    }

}
