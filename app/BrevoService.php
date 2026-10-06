<?php

declare(strict_types=1);

namespace App;

use Brevo\Brevo;
use Brevo\Contacts\Requests\CreateContactRequest;
use Illuminate\Support\Facades\Log;

class BrevoService
{
    private $client;
    public function __construct()
    {
        $this->client = new Brevo(apiKey: (string) config('neayi.brevo_api_key'));
    }

    public function addEmailToList(string $email, string $name, string $firstName)
    {
        try {
            $this->client->contacts->createContact(new CreateContactRequest([
                'email' => $email,
                'listIds' => [4],
                'attributes' => [
                    'NOM' => $name,
                    'PRENOM' => $firstName
                ],
                'updateEnabled' => true,
            ]));
            Log::info('Email added to brevo : ' . $email);
        } catch (\Throwable $e) {
            Log::error('Exception when calling Brevo contacts->createContact: '. $e->getMessage());
        }
    }
}
