<?php

namespace App\Console\Commands\Debug;

use App\MailChimpService;
use Illuminate\Console\Command;

class TestMailChimp extends Command
{
    protected $signature = 'debug:mailchimp {email}';

    protected $description = 'Add email to mailchimp';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle(MailChimpService $mailChimpService)
    {
        $email = $this->argument('email');

        $mailChimpService->addEmailToList($email);
    }
}
