<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class InvitationLinkToOrganization extends Mailable
{
    use Queueable, SerializesModels;

    private $hash;
    private $email;
    private $firstname;
    private $lastname;
    private $organization;

    public function __construct(string $hash, string $email, string $organization, string $firstname = null, string $lastname = null)
    {
        $this->hash = $hash;
        $this->email = $email;
        $this->firstname = $firstname;
        $this->lastname = $lastname;
        $this->organization = $organization;
    }

    public function build()
    {
        $link = route('organization.invite.show').'?&token='.$this->hash;
        return $this->view('mails.invitationLinkToOrganization', [
            'link' => $link,
            'firstname' => $this->firstname,
            'organization' => $this->organization
        ]);
    }
}
