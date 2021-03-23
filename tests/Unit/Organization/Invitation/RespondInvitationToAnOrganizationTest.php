<?php


namespace Tests\Unit\Organization\Invitation;


use App\Src\UseCases\Domain\Organizations\Invitation\RespondInvitationToAnOrganization;
use App\Src\UseCases\Domain\Organizations\Model\Address;
use App\Src\UseCases\Domain\Organizations\Model\Invitation;
use App\Src\UseCases\Domain\Organizations\Model\Organization;
use App\Src\UseCases\Domain\User;
use Ramsey\Uuid\Uuid;
use Tests\TestCase;

class RespondInvitationToAnOrganizationTest extends TestCase
{
    public function testShouldAskUserToRegisterWhenItDoesNotExist()
    {
        $organizationId = Uuid::uuid4();
        $email = 'anemailofunknowuser@gmail.com';

        $invitation = new Invitation($organizationId, $email);
        $this->invitationRepository->add($invitation);

        $action = app(RespondInvitationToAnOrganization::class)->respond($invitation->hash());

        $actionExpected = [
            'action' => 'register',
            'organization_id' => $organizationId->toString(),
            'user' => [
                'email' => $email,
                'firstname' => '',
                'lastname' => '',
            ]
        ];
        self::assertEquals($actionExpected, $action);
    }

    public function testShouldAskUserToAcceptOrDeclineInvitation()
    {
        $organizationId = Uuid::uuid4();
        $email = 'user@gmail.com';

        $userId = Uuid::uuid4();
        $user = new User($userId, $email, 'firstname', 'lastname');
        $this->userRepository->add($user);
        $this->authGateway->log($user);

        $address = new Address('la garde', '1', '2', '83130');
        $organizationToJoin = new Organization($organizationId, 'org_to_join', '', $address);
        $this->organizationRepository->add($organizationToJoin);

        $invitation = new Invitation($organizationId, $email);
        $this->invitationRepository->add($invitation);

        $action = app(RespondInvitationToAnOrganization::class)->respond($invitation->hash());

        $actionExpected = [
            'action' => 'accept_or_decline',
            'old_organisation' => null,
            'organization_to_join' => $organizationToJoin
        ];
        self::assertEquals($actionExpected, $action);
    }

    public function testShouldAskUserToAcceptOrDeclineInvitation_WhenHeIsAlreadyInOtherOrganization()
    {
        $organizationId = Uuid::uuid4();
        $email = 'user@gmail.com';

        $userId = Uuid::uuid4();
        $user = new User($userId, $email, 'firstname', 'lastname', $oldOrganizationId = Uuid::uuid4()->toString());
        $this->userRepository->add($user);
        $this->authGateway->log($user);

        $address = new Address('la garde', '1', '2', '83130');
        $organizationToJoin = new Organization($organizationId, 'org_to_join', '', $address);
        $this->organizationRepository->add($organizationToJoin);

        $address = new Address('la garde', '1', '2', '83130');
        $oldOrganization = new Organization($oldOrganizationId, 'old_org', '', $address);
        $this->organizationRepository->add($oldOrganization);

        $invitation = new Invitation($organizationId, $email);
        $this->invitationRepository->add($invitation);

        $action = app(RespondInvitationToAnOrganization::class)->respond($invitation->hash());

        $actionExpected = [
            'action' => 'accept_or_decline',
            'old_organisation' => $oldOrganization,
            'organization_to_join' => $organizationToJoin
        ];
        self::assertEquals($actionExpected, $action);
    }

    public function testShouldAskUserToLogoutThenLoginToCorrectAccount_WhenUserExists()
    {
        $organizationId = Uuid::uuid4();
        $email = 'user@gmail.com';

        $userId = Uuid::uuid4();
        $user = new User($userId, $email, 'firstname', 'lastname', $oldOrganizationId = Uuid::uuid4()->toString());
        $this->userRepository->add($user);

        $userLoggedId = Uuid::uuid4();
        $userLogged = new User($userLoggedId, 'anemail@gmail.com', 'firstname', 'lastname', $oldOrganizationId = Uuid::uuid4()->toString());
        $this->userRepository->add($userLogged);
        $this->authGateway->log($userLogged);

        $address = new Address('la garde', '1', '2', '83130');
        $organizationToJoin = new Organization($organizationId, 'org_to_join', '', $address);
        $this->organizationRepository->add($organizationToJoin);

        $invitation = new Invitation($organizationId, $email);
        $this->invitationRepository->add($invitation);

        $action = app(RespondInvitationToAnOrganization::class)->respond($invitation->hash());

        $actionExpected = [
            'action' => 'logout-login',
            'organization_to_join' => $organizationToJoin
        ];
        self::assertEquals($actionExpected, $action);
    }

    public function testShouldAskUserToLogoutThenRegisterNewAccount_WhenUserDoesNotExist()
    {
        $organizationId = Uuid::uuid4();
        $email = 'user@gmail.com';

        $userLoggedId = Uuid::uuid4();
        $userLogged = new User($userLoggedId, 'anemail@gmail.com', 'firstname', 'lastname', $oldOrganizationId = Uuid::uuid4()->toString());
        $this->userRepository->add($userLogged);
        $this->authGateway->log($userLogged);

        $address = new Address('la garde', '1', '2', '83130');
        $organizationToJoin = new Organization($organizationId, 'org_to_join', '', $address);
        $this->organizationRepository->add($organizationToJoin);

        $invitation = new Invitation($organizationId, $email);
        $this->invitationRepository->add($invitation);

        $action = app(RespondInvitationToAnOrganization::class)->respond($invitation->hash());

        $actionExpected = [
            'action' => 'logout-register',
            'organization_to_join' => $organizationToJoin,
            'user' => [
                'email' => $email,
                'firstname' => '',
                'lastname' => '',
            ]
        ];
        self::assertEquals($actionExpected, $action);
    }

    public function testShouldAskUserToLogin()
    {
        $organizationId = Uuid::uuid4();
        $email = 'user@gmail.com';

        $userId = Uuid::uuid4();
        $user = new User($userId, $email, 'firstname', 'lastname', $oldOrganizationId = Uuid::uuid4()->toString());
        $this->userRepository->add($user);

        $address = new Address('la garde', '1', '2', '83130');
        $organizationToJoin = new Organization($organizationId, 'org_to_join', '', $address);
        $this->organizationRepository->add($organizationToJoin);

        $invitation = new Invitation($organizationId, $email);
        $this->invitationRepository->add($invitation);

        $action = app(RespondInvitationToAnOrganization::class)->respond($invitation->hash());

        $actionExpected = [
            'action' => 'login',
            'organization_to_join' => $organizationToJoin
        ];
        self::assertEquals($actionExpected, $action);
    }
}
