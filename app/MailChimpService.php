<?php

declare(strict_types=1);

namespace App;

use GuzzleHttp\Exception\ClientException;
use Illuminate\Support\Facades\Log;
use MailchimpMarketing\ApiClient;
use function Sentry\captureException;

class MailChimpService
{

    private $mailchimp;
    const LIST_ID = '87f0d01da9';
    const TAG = ['Inscrits Triple Performance'];
    const STATUS = 'subscribed';

    public function __construct()
    {
        $this->mailchimp = new ApiClient();

        $this->mailchimp->setConfig([
            'apiKey' => config('neayi.mailchimp_api_key'),
            'server' => 'us18'
        ]);
    }

    public function addEmailToList(string $email)
    {
        try {
            $this->mailchimp->lists->addListMember(self::LIST_ID, [
                "email_address" => $email,
                "status" => self::STATUS,
                "tags" => self::TAG
            ]);
            Log::info('Email added to mailchimp : ' . $email);
        } catch (ClientException $e) {
            $response = json_decode($e->getResponse()->getBody()->getContents());
            if (isset($response->title) && $response->title === 'Member Exists') {
                Log::info('Email already exists in mailchimp : ' . $email);
                return;
            }
            Log::critical('Error when adding email to mailchimp : ' . $email);
            captureException($e);
        }
    }
}
