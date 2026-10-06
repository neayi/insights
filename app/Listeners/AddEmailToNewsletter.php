<?php

declare(strict_types=1);

namespace App\Listeners;

use App\MailerLiteService;
use App\BrevoService;
use Illuminate\Auth\Events\Verified;
use Illuminate\Support\Facades\Log;

class AddEmailToNewsletter
{
    private $mailerLiteService;
    private $brevoService;

    public function __construct(
        MailerLiteService $mailerLiteService,
        BrevoService $brevoService
    )
    {
        $this->mailerLiteService = $mailerLiteService;
        $this->brevoService = $brevoService;
    }

    public function handle(Verified $verified)
    {
        if (app()->environment('local', 'testing')) {
            return;
        }

        try {
            $this->brevoService->addEmailToList($verified->user->email, $verified->user->lastname, $verified->user->firstname);
        } catch (\Throwable $e) {
            Log::warning('Error when adding email to brevo : ' . $verified->user->email . ' - ' . $e->getMessage());
        }
        try {
            $this->mailerLiteService->addEmailToList($verified->user->email);
        } catch (\Exception $e) {
            Log::warning('Error when adding email to mailerlite : ' . $verified->user->email . ' - ' . $e->getMessage());
        }
    }
}
