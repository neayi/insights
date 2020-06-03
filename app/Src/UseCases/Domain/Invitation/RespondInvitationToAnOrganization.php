<?php


namespace App\Src\UseCases\Domain\Invitation;


use App\Src\UseCases\Domain\Ports\UserRepository;

class RespondInvitationToAnOrganization
{
    private $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function respond(string $token)
    {
        list($organizationId, $email, $firstname, $lastname) = explode('|*|', base64_decode($token));
        $user = $this->userRepository->getByEmail($email);

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
        return [
            'action' => 'accept_or_decline'
        ];
    }
}
