<?php


namespace App\Src\UseCases\Domain;


use App\Mail\InvitationLinkToOrganization;
use Illuminate\Support\Facades\Mail;

class InviteUsersInOrganization
{
    public function invite(string $organizationId, array $users)
    {
        foreach($users as $user){
            $email = $user['email'];
            $firstname = isset($user['firstname']) ? $user['firstname'] : '';
            $lastname = isset($user['lastname']) ? $user['lastname'] : '';
            $token = base64_encode($organizationId.'|*|'.$email.'|*|'.$firstname.'|*|'.$lastname);
            Mail::to($email)->send(new InvitationLinkToOrganization($token, $email, $firstname, $lastname));
        }
    }
}
