<?php


namespace App\Src\UseCases\Domain\Invitation;


use App\Src\UseCases\Domain\Ports\OrganizationRepository;
use App\Src\UseCases\Domain\Ports\UserRepository;

class RespondInvitationToAnOrganization
{
    private $userRepository;
    private $organizationRepository;

    public function __construct(
        UserRepository $userRepository,
        OrganizationRepository $organizationRepository
    )
    {
        $this->userRepository = $userRepository;
        $this->organizationRepository = $organizationRepository;
    }

    public function respond(string $token)
    {
        list($organizationId, $email, $firstname, $lastname) = explode('|*|', base64_decode($token));
        $user = $this->userRepository->getByEmail($email);

        $organizationToJoin = $this->organizationRepository->get($organizationId);

        if(!isset($user)){
            return [
                'action' => 'register',
                'organization_id' => $organizationId,
                'user' => [
                    'email' => $email,
                    'firstname' => $firstname,
                    'lastname' => $lastname,
                ]
            ];
        }

        $organization = null;
        if($user->organizationId() !==null) {
            $organization = $this->organizationRepository->get($user->organizationId());
        }

        return [
            'action' => 'accept_or_decline',
            'old_organisation' => $organization,
            'organization_to_join' => $organizationToJoin
        ];
    }
}
