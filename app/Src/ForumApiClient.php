<?php

declare(strict_types=1);

namespace App\Src;

use App\User;
use GuzzleHttp\Client;

class ForumApiClient
{
    private Client $client;

    public function __construct(string $baseUri, string $apiKey)
    {
        $this->client = new Client([
            'base_uri' => $baseUri,
            'headers' => [
                'Api-Key' => $apiKey,
                'Api-Username' => 'system',
                'Content-Type' => 'application/json'
            ]
        ]);
    }

    public function getUserByUsername(string $username): array
    {
        $response = $this->client->get('search.json?q=order:latest @'.$username);
        usleep(500000);

        return json_decode($response->getBody()->getContents(), true);
    }

    public function getUserByEmail(string $email): array
    {
        $response = $this->client->get('/admin/users/list/active.json?filter=' . $email . '&show_emails=true&order=&ascending=&page=1');
        usleep(500000);

        return json_decode($response->getBody()->getContents(), true);
    }

    public function getUserByInsightId(int $id): array
    {
        $result = $this->client->get('u/by-external/' . $id . '.json');
        usleep(500000);

        return json_decode($result->getBody()->getContents(), true);
    }

    /**
     * Using API to change email is not possible when SSO DiscourseConnect is enabled.
     */
    public function updateEmail(string $username, string $email): array
    {
        throw new \RuntimeException('Updating email via API is not possible when SSO DiscourseConnect is enabled.');

        $result = $this->client->put('u/' . $username . '/preferences/email.json', [
            'json' => [
                'email' => $email,
            ]
        ]);
        usleep(500000);

        return json_decode($result->getBody()->getContents(), true);
    }

    public function updateUser(string $discourseUsername, User $user, string $bio): array
    {
        $result = $this->client->put('u/' . $discourseUsername . '.json', [
            'json' => [
                'name' => $user->fullname,
                'title' => $user->title,
                'bio_raw' => $bio,
            ]
        ]);
        usleep(500000);

        return json_decode($result->getBody()->getContents(), true);
    }

    public function createUser(string $username, User $user): array
    {
        $result = $this->client->post('users.json', [
            'json' => [
                'username' => $username,
                'name' => $user->fullname,
                'password' => uniqid().uniqid(),
                'email' => $user->email,
                'active' => true,
                'approved'=> true
            ]
        ]);
        usleep(500000);

        return json_decode($result->getBody()->getContents(), true);
    }

    public function getTagGroup(int $tagGroupId): array
    {
        $result = $this->client->get('tag_groups/' . $tagGroupId . '.json');
        usleep(500000);

        return json_decode($result->getBody()->getContents(), true);
    }

    public function updateTagGroup(int $tagGroupId, array $tagNames): array
    {
        asort($tagNames);
        $tagNames = array_values(array_unique($tagNames));

        $payload = [
            'tag_group' => [
                'tag_names' => $tagNames,
            ],
        ];

        $result = $this->client->put('tag_groups/' . $tagGroupId . '.json', ['json' => $payload]);
        usleep(500000);

        return json_decode($result->getBody()->getContents(), true);
    }

    public function subscribeTagNotifications(string $username, string $tagName): array
    {
        $result = $this->client->put(
            'tag/' . urlencode($tagName) . '/notifications',
            [
                'form_params' => [
                    'tag_notification' => [
                        'notification_level' => '2',
                    ],
                ],
                // Replaces API-Username header by user username
                'headers' => [
                    'Api-Username' => $username,
                ]
            ]
        );
        usleep(500000);

        return json_decode($result->getBody()->getContents(), true);
    }

    public function getFollowedTagsForUser(string $username): array
    {
        $result = $this->client->get(
            'u/' . urlencode($username) . '.json'
        );

        $userData = json_decode($result->getBody()->getContents(), true);
        usleep(500000);

        return $userData['user']['tracked_tags'] ?? [];
    }

}
