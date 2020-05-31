<?php


namespace App\Src\UseCases\Domain\Services;


use App\Mail\UserJoinsOrganizationToUser;
use App\Src\UseCases\Domain\Ports\UserRepository;
use Illuminate\Support\Facades\Mail;

class SendMailToUserWhenHeJoinsOrganization
{
    private $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function send(string $userId)
    {
        $user = $this->userRepository->getById($userId);
        Mail::to($user->email())->send(new UserJoinsOrganizationToUser());
    }
}
