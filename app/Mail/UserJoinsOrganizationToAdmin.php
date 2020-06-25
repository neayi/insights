<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class UserJoinsOrganizationToAdmin extends Mailable
{
    use Queueable, SerializesModels;

    private $fullname;
    private $organization;

    public function __construct(string $fullname, string $organization)
    {
        $this->fullname = $fullname;
        $this->organization = $organization;
    }

    public function build()
    {
        return $this->view('mails.userJoinsOrganizationToAdmin', [
            'fullname' => $this->fullname,
            'organization' => $this->organization
        ]);
    }
}
