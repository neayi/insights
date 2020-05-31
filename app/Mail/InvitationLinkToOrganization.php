<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class InvitationLinkToOrganization extends Mailable
{
    use Queueable, SerializesModels;

    private $token;
    private $email;
    private $firstname;
    private $lastname;

    public function __construct(string $token, string $email, string $firstname = null, string $lastname = null)
    {
        $this->token = $token;
        $this->email = $email;
        $this->firstname = $firstname;
        $this->lastname = $lastname;
    }

    public function build()
    {
        $link = route('organization.invite.show').'?token='.$this->token;
        return $this->view('mails.invitationLinkToOrganization', [
            'link' => $link
        ]);
    }
}
