<?php


namespace App\Src\Organizations\Invitation;


use App\Mail\UserJoinsOrganizationToAdmin;
use App\Src\Organizations\OrganizationRepository;
use App\Src\Users\User;
use App\Src\Users\UserRepository;
use Illuminate\Support\Facades\Mail;

class AttachUserToAnOrganization
{
    private $userRepository;
    private $organizationRepository;

    public function __construct(UserRepository $userRepository, OrganizationRepository $organizationRepository)
    {
        $this->userRepository = $userRepository;
        $this->organizationRepository = $organizationRepository;
    }

    public function attach(string $userId, string $organizationId)
    {
        $user = $this->userRepository->getById($userId);
        $user->joinsOrganization($organizationId);

        $this->sendMailUserJoinsToAdmin($organizationId, $user);
    }

    private function sendMailUserJoinsToAdmin(string $organizationId, User $user): void
    {
        $admins = $this->userRepository->getAdminOfOrganization($organizationId);
        $fullname = $user->fullname();
        $organization = $this->organizationRepository->get($organizationId);
        foreach ($admins as $admin) {
            Mail::to($admin->email())->send(new UserJoinsOrganizationToAdmin($fullname, $organization->name()));
        }
    }
}
