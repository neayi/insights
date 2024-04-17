<?php

namespace App\Console\Commands\Debug;

use App\MailChimpService;
use App\SendinBlueService;
use App\MailerLiteService;
use Illuminate\Console\Command;

class TestMailChimp extends Command
{
    protected $signature = 'debug:mailchimp {email}';

    protected $description = 'Add email to newsletter';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle(MailChimpService $mailChimpService,
                           SendinBlueService $sendinBlueService,
                           MailerLiteService $mailerLiteService)
    {
        $email = $this->argument('email');

        $mailChimpService->addEmailToList($email);
        $sendinBlueService->addEmailToList($email, 'jean', 'dupont');
        $mailerLiteService->addEmailToList($email);
    }
}
