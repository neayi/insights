<?php

namespace App\Listeners;

use App\MailChimpService;
use Illuminate\Auth\Events\Verified;

class AddEmailToMailChimp
{
    private $mailchimpService;
    public function __construct(MailChimpService $mailChimpService)
    {
        $this->mailchimpService = $mailChimpService;
    }

    public function handle(Verified $verified)
    {
        $this->mailchimpService->addEmailToList($verified->user->email);
    }
}
