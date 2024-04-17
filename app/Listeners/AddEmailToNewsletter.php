<?php

declare(strict_types=1);

namespace App\Listeners;

use App\MailerLiteService;
use App\SendinBlueService;
use Illuminate\Auth\Events\Verified;
use Illuminate\Support\Facades\Log;

class AddEmailToNewsletter
{
    private $mailerLiteService;
    private $sendingBlueService;

    public function __construct(
        MailerLiteService $mailerLiteService,
        SendinBlueService $sendingBlueService
    )
    {
        $this->mailerLiteService = $mailerLiteService;
        $this->sendingBlueService = $sendingBlueService;
    }

    public function handle(Verified $verified)
    {
        try {
            $this->sendingBlueService->addEmailToList($verified->user->email, $verified->user->lastname, $verified->user->firstname);
        } catch (\Throwable $e) {
            Log::critical('Error when adding email to sending blue : ' . $verified->user->email);
        }
        try {
            $this->mailerLiteService->addEmailToList($verified->user->email);
        } catch (\Exception $e) {
            Log::critical('Error when adding email to mailerlite : ' . $verified->user->email);
        }
    }
}
