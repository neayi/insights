<?php

declare(strict_types=1);

namespace App;

use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;
use SendinBlue\Client\Api\ContactsApi;
use SendinBlue\Client\Configuration;
use SendinBlue\Client\Model\CreateContact;

class SendinBlueService
{
    private $client;
    public function __construct()
    {
        $config = Configuration::getDefaultConfiguration()
            ->setApiKey('api-key', config('neayi.sendinblue_api_key'));

        $this->client = new ContactsApi(
            new Client(),
            $config
        );
    }

    public function addEmailToList(string $email)
    {
        try {
            $createContact = new CreateContact();
            $createContact['email'] = $email;
            $createContact['listIds'] = [4];
            $this->client->createContact($createContact);
            Log::info('Email added to sendinblue : ' . $email);
        } catch (\Throwable $e) {
            Log::error('Exception when calling ContactsApi->createContact: '. $e->getMessage());
        }
    }
}
