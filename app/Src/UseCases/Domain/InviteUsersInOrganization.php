<?php


namespace App\Src\UseCases\Domain;


use App\Mail\InvitationLinkToOrganization;
use Illuminate\Support\Facades\Mail;

class InviteUsersInOrganization
{
    public function invite(string $organizationId, array $emails)
    {
        foreach($emails as $email){
            $token = base64_encode($organizationId.'*'.$email);
            Mail::to($email)->send(new InvitationLinkToOrganization());
        }
    }
}
