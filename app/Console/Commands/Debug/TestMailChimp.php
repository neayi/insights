<?php

namespace App\Console\Commands\Debug;

use App\GeoOpenDataSoftService;
use App\MailChimpService;
use App\SendinBlueService;
use Illuminate\Console\Command;

class TestMailChimp extends Command
{
    protected $signature = 'debug:mailchimp {email}';

    protected $description = 'Add email to newsletter';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle(MailChimpService $mailChimpService, SendinBlueService $sendinBlueService)
    {
        $email = $this->argument('email');

        //$mailChimpService->addEmailToList($email);
        //$sendinBlueService->addEmailToList($email, 'jean', 'dupont');

        (new GeoOpenDataSoftService())->getGeolocationByPostalCode('12000');

    }
}
