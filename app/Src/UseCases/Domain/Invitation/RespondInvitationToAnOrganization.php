<?php


namespace App\Src\UseCases\Domain\Invitation;


use App\Src\UseCases\Domain\Organization;
use App\Src\UseCases\Domain\Ports\InvitationRepository;
use App\Src\UseCases\Domain\Ports\OrganizationRepository;
use App\Src\UseCases\Domain\Ports\UserRepository;
use App\Src\UseCases\Domain\User;
use App\Src\UseCases\Infra\Gateway\Auth\AuthGateway;

class RespondInvitationToAnOrganization
{
    private $userRepository;
    private $organizationRepository;
    private $authGateway;
    private $invitationRepository;

    public function __construct(
        UserRepository $userRepository,
        OrganizationRepository $organizationRepository,
        AuthGateway $authGateway,
        InvitationRepository $invitationRepository
    )
    {
        $this->userRepository = $userRepository;
        $this->organizationRepository = $organizationRepository;
        $this->authGateway = $authGateway;
        $this->invitationRepository = $invitationRepository;
    }

    public function respond(string $hash)
    {
        $invitation = $this->invitationRepository->getByHash($hash);
        list($organizationId, $email, $firstname, $lastname) = $invitation->data();
        $user = $this->userRepository->getByEmail($email);
        $organizationToJoin = $this->organizationRepository->get($organizationId);

        $currentUser = $this->authGateway->current();
        if(isset($currentUser) && $currentUser->email() !== $email){
            $action = 'logout';
            $action .= !isset($user) ? '-register' : '-login';

            if($action == 'logout-login') {
                return $this->badUserLoginToAcceptAction($action, $organizationToJoin);
            }
            return $this->returnLogoutAction($action, $organizationToJoin, $email, $firstname, $lastname);
        }


        if(!isset($user)){
            return $this->returnRegisterAction($organizationId, $email, $firstname, $lastname);
        }

        $currentUser = $this->authGateway->current();
        if(!isset($currentUser)){
            return $this->returnLoginAction($organizationToJoin);
        }

        return $this->acceptOrDeclineAction($organizationToJoin, $currentUser);
    }


    private function acceptOrDeclineAction(?Organization $organizationToJoin, User $user): array
    {
        $organization = $this->getCurrentOrganization($user);
        return [
            'action' => 'accept_or_decline',
            'old_organisation' => $organization,
            'organization_to_join' => $organizationToJoin
        ];
    }

    private function returnLoginAction(?Organization $organizationToJoin): array
    {
        return [
            'action' => 'login',
            'organization_to_join' => $organizationToJoin,
        ];
    }

    private function returnRegisterAction($organizationId, $email, $firstname, $lastname): array
    {
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

    private function returnLogoutAction(string $action, ?Organization $organizationToJoin, $email, $firstname, $lastname): array
    {
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

    private function badUserLoginToAcceptAction(string $action, ?Organization $organizationToJoin): array
    {
        return [
            'action' => $action,
            'organization_to_join' => $organizationToJoin,
        ];
    }

    private function getCurrentOrganization(User $user)
    {
        $organization = null;
        if ($user->organizationId() !== null) {
            $organization = $this->organizationRepository->get($user->organizationId());
        }
        return $organization;
    }
}
