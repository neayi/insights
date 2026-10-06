<?php

namespace App\Console\Commands\Debug;

use App\BrevoService;
use App\MailerLiteService;
use Illuminate\Console\Command;

class TestMailers extends Command
{
    protected $signature = 'debug:mailers {email}';

    protected $description = 'Add email to newsletter';

    public function handle(BrevoService $brevoService,
                           MailerLiteService $mailerLiteService)
    {
        $email = $this->argument('email');

        $brevoService->addEmailToList($email, 'jean', 'dupont');
        $mailerLiteService->addEmailToList($email);
    }
}
