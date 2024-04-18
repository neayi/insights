<?php

declare(strict_types=1);

namespace App;

use Illuminate\Support\Facades\Log;
use MailerLite\MailerLite;

use function Sentry\captureException;

class MailerLiteService
{

    private $mailerlite;
    const GROUP_ID = '118792092290909809';
    const STATUS = 'active';

    public function __construct()
    {
        $this->mailerlite = new MailerLite(['api_key' => config('neayi.mailerlite_api_key')]);
    }

    public function addEmailToList(string $email)
    {
        try {
            $this->mailerlite->subscribers->create([
                'email' => $email,
                'groups' => [self::GROUP_ID],
                'status' => self::STATUS
            ]);

            Log::info('Email added to mailerlite : ' . $email);
        } catch (\Exception $e) {
            Log::critical('Error when adding email to mailerlite: ' . $email . ' - ' . $e->getMessage());
            captureException($e);
        }
    }
}
