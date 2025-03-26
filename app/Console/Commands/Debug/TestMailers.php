<?php

namespace App\Console\Commands\Debug;

use App\SendinBlueService;
use App\MailerLiteService;
use Illuminate\Console\Command;

class TestMailers extends Command
{
    protected $signature = 'debug:mailers {email}';

    protected $description = 'Add email to newsletter';

    public function handle(SendinBlueService $sendinBlueService,
                           MailerLiteService $mailerLiteService)
    {
        $email = $this->argument('email');

        $sendinBlueService->addEmailToList($email, 'jean', 'dupont');
        $mailerLiteService->addEmailToList($email);
    }
}
