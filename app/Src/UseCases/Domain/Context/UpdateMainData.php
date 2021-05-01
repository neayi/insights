<?php


namespace App\Src\UseCases\Domain\Context;


use App\Src\UseCases\Domain\Ports\ContextRepository;
use App\Src\UseCases\Domain\Ports\UserRepository;
use App\Src\UseCases\Domain\Shared\Gateway\AuthGateway;

class UpdateMainData
{
    private $contextRepository;
    private $authGateway;
    private $userRepository;

    public function __construct(
        ContextRepository $contextRepository,
        AuthGateway $authGateway,
        UserRepository $userRepository
    )
    {
        $this->contextRepository = $contextRepository;
        $this->authGateway = $authGateway;
        $this->userRepository = $userRepository;
    }

    public function execute(string $postalCode, string $sector, string $structure, string $email, string $firstname, string $lastname, string $role)
    {
        $currentUser = $this->authGateway->current();
        $context = $this->contextRepository->getByUser($currentUser->id());

        $user = $this->userRepository->getById($currentUser->id());
        $user->update($email, $firstname, $lastname);
        $user->updateRole($role);
        $context->update([
            'postal_code' => $postalCode,
            'sector' => $sector,
            'structure' => $structure,
        ], $currentUser->id());
    }
}
