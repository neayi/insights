<?php


namespace App\Src\UseCases\Domain;


use App\Mail\InvitationLinkToOrganization;
use App\Src\UseCases\Domain\Ports\OrganizationRepository;
use Illuminate\Support\Facades\Mail;

class InviteUsersInOrganization
{
    private $organizationRepository;

    public function __construct(OrganizationRepository $organizationRepository)
    {
        $this->organizationRepository = $organizationRepository;
    }

    public function invite(string $organizationId, array $users)
    {
        $organization = $this->organizationRepository->get($organizationId);
        foreach($users as $user){
            $email = $user['email'];
            $firstname = isset($user['firstname']) ? $user['firstname'] : '';
            $lastname = isset($user['lastname']) ? $user['lastname'] : '';
            $token = base64_encode($organizationId.'|*|'.$email.'|*|'.$firstname.'|*|'.$lastname);
            Mail::to($email)->send(new InvitationLinkToOrganization($token, $email, $organization->name(), $firstname, $lastname));
        }
    }
}
