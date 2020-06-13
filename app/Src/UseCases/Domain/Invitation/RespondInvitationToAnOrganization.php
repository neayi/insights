<?php


namespace App\Src\UseCases\Domain\Invitation;


use App\Src\UseCases\Domain\Ports\OrganizationRepository;
use App\Src\UseCases\Domain\Ports\UserRepository;
use App\Src\UseCases\Infra\Gateway\Auth\AuthGateway;

class RespondInvitationToAnOrganization
{
    private $userRepository;
    private $organizationRepository;
    private $authGateway;

    public function __construct(
        UserRepository $userRepository,
        OrganizationRepository $organizationRepository,
        AuthGateway $authGateway
    )
    {
        $this->userRepository = $userRepository;
        $this->organizationRepository = $organizationRepository;
        $this->authGateway = $authGateway;
    }

    public function respond(string $token)
    {
        list($organizationId, $email, $firstname, $lastname) = explode('|*|', base64_decode($token));
        $user = $this->userRepository->getByEmail($email);

        $organizationToJoin = $this->organizationRepository->get($organizationId);

        $currentUser = $this->authGateway->current();
        if(isset($currentUser) && $currentUser->email() !== $email){
            $action = 'logout';
            $action .= !isset($user) ? '-register' : '-login';

            if($action == 'logout-login') {
                return [
                    'action' => $action,
                    'organization_to_join' => $organizationToJoin,
                ];
            }

            return [
                'action' => $action,
                'organization_to_join' => $organizationToJoin,
                'user' => [
                    'email' => $email,
                    'firstname' => $firstname,
                    'lastname' => $lastname,
                ]
            ];
        }


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

        $currentUser = $this->authGateway->current();
        if(!isset($currentUser)){
            return [
                'action' => 'login',
                'organization_to_join' => $organizationToJoin,
            ];
        }

        $organization = null;
        if($user->organizationId() !== null) {
            $organization = $this->organizationRepository->get($user->organizationId());
        }

        return [
            'action' => 'accept_or_decline',
            'old_organisation' => $organization,
            'organization_to_join' => $organizationToJoin
        ];
    }
}
