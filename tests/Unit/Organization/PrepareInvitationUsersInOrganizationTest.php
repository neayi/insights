<?php


namespace Tests\Unit\Organization;


use App\Src\UseCases\Domain\Organizations\Invitation\PrepareInvitationUsersInOrganization;
use App\Src\UseCases\Domain\User;
use Ramsey\Uuid\Uuid;
use Tests\TestCase;

class PrepareInvitationUsersInOrganizationTest extends TestCase
{
    public function testShouldIgnoreInvalidMails()
    {
        $organizationId = Uuid::uuid4();
        $emails = ['anemail', 'anotheremail@gmail.com'];

        $usersToProcess = app(PrepareInvitationUsersInOrganization::class)->prepare($organizationId, $emails);

        $userExpectedToProcess = ['users' => [[
                'email' => 'anemail',
                'error' => 'email.error.syntax'
            ], [
                'email' => 'anotheremail@gmail.com',
            ]],
            'total' => 2,
            'imported' => 1,
            'error' => 1
        ];
        self::assertEquals($usersToProcess, $userExpectedToProcess);
    }

    public function  testShouldNotInviteWhenUserAlreadyInOrganization()
    {
        $organizationId = Uuid::uuid4();
        $emails = [$email = 'auseralreadyinOrga@gmail.com', 'anotheremail@gmail.com'];
        $user = new User(Uuid::uuid4()->toString(), $email, '', '', $organizationId);
        $this->userRepository->add($user);

        $usersToProcess = app(PrepareInvitationUsersInOrganization::class)->prepare($organizationId, $emails);

        $userExpectedToProcess = [
            'users' => [
                ['email' => 'auseralreadyinOrga@gmail.com', 'error' => 'already_in'],
                ['email' => 'anotheremail@gmail.com'],
            ]
        ];
        self::assertEquals($usersToProcess['users'], $userExpectedToProcess['users']);
    }

    public function  testShouldNotInviteUserTwice()
    {
        $organizationId = Uuid::uuid4();
        $emails = [$email = 'anotheremail@gmail.com', 'anotheremail@gmail.com'];

        $usersToProcess = app(PrepareInvitationUsersInOrganization::class)->prepare($organizationId, $emails);

        $userExpectedToProcess = ['users' => [['email' => 'anotheremail@gmail.com']]];
        self::assertEquals($usersToProcess['users'], $userExpectedToProcess['users']);
    }

    public function  testShouldInviteUser_WithFileInput()
    {
        $organizationId = Uuid::uuid4();
        $emails = [
            [
                'email' => 'anotheremail@gmail.com',
                'firstname' => 'prenom',
                'lastname' => 'nom'
            ],
            [
                'email' => 'anotheremail2@gmail.com'
            ]
        ];

        $path = 'pathfile.csv';
        $this->fileStorage->setContent($path, $emails);
        $usersToProcess = app(PrepareInvitationUsersInOrganization::class)->prepare($organizationId, [], $path);

        $userExpectedToProcess = [
            'users' => [
                [
                    'email' => 'anotheremail@gmail.com',
                    'firstname' => 'prenom',
                    'lastname' => 'nom'
                ],
                [
                    'email' => 'anotheremail2@gmail.com'
                ]
            ]
        ];
        self::assertEquals($usersToProcess['users'], $userExpectedToProcess['users']);
    }
}
