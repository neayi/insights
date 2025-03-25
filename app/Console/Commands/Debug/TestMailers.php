<?php

namespace App\Console\Commands\Debug;

use App\SendinBlueService;
use App\MailerLiteService;
use Illuminate\Console\Command;

class TestMailers extends Command
{
    public function handle(SendinBlueService $sendinBlueService,
                           MailerLiteService $mailerLiteService)
    {
        $email = $this->argument('email');

        $sendinBlueService->addEmailToList($email, 'jean', 'dupont');
        $mailerLiteService->addEmailToList($email);
    }
}
