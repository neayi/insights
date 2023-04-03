<?php

declare(strict_types=1);

namespace App\Listeners;

use App\MailChimpService;
use App\SendinBlueService;
use Illuminate\Auth\Events\Verified;
use Illuminate\Support\Facades\Log;

class AddEmailToNewsletter
{
    private $mailchimpService;
    private $sendingBlueService;

    public function __construct(
        MailChimpService $mailChimpService,
        SendinBlueService $sendingBlueService
    )
    {
        $this->mailchimpService = $mailChimpService;
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
            $this->mailchimpService->addEmailToList($verified->user->email);
        } catch (\Exception $e) {
            Log::critical('Error when adding email to mailchimp : ' . $verified->user->email);
        }
    }
}
