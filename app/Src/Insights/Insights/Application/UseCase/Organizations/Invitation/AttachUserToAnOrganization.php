<?php


namespace App\Src\Insights\Insights\Application\UseCase\Organizations\Invitation;


use App\Mail\UserJoinsOrganizationToAdmin;
use App\Src\UseCases\Domain\Ports\OrganizationRepository;
use App\Src\UseCases\Domain\Ports\UserRepository;
use App\Src\UseCases\Domain\User;
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
