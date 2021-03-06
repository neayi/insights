<?php


namespace App\Src\UseCases\Domain\Organizations\Invitation;


use App\Mail\InvitationLinkToOrganization;
use App\Src\UseCases\Domain\Organizations\Model\Invitation;
use App\Src\UseCases\Domain\Ports\InvitationRepository;
use App\Src\UseCases\Domain\Ports\OrganizationRepository;
use Illuminate\Support\Facades\Mail;

class InviteUsersInOrganization
{
    private $organizationRepository;
    private $invitationRepository;

    public function __construct(
        OrganizationRepository $organizationRepository,
        InvitationRepository $invitationRepository
    )
    {
        $this->organizationRepository = $organizationRepository;
        $this->invitationRepository = $invitationRepository;
    }

    public function invite(string $organizationId, array $users)
    {
        $invitations = [];
        $organization = $this->organizationRepository->get($organizationId);
        foreach($users as $user){
            $email = $user['email'];
            $firstname = isset($user['firstname']) ? $user['firstname'] : '';
            $lastname = isset($user['lastname']) ? $user['lastname'] : '';

            $invitations[] = $invitation = new Invitation($organizationId, $email, $firstname, $lastname);
            $this->invitationRepository->add($invitation);
            Mail::to($email)->send(new InvitationLinkToOrganization($invitation->hash(), $email, $organization->name(), $firstname, $lastname));
        }
        return $invitations;
    }
}
