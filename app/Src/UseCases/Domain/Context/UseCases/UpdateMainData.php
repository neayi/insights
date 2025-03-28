<?php


declare(strict_types=1);

namespace App\Src\UseCases\Domain\Context\UseCases;


use App\Src\UseCases\Domain\Ports\ContextRepository;
use App\Src\UseCases\Domain\Ports\UserRepository;
use App\Src\UseCases\Domain\Shared\Gateway\AuthGateway;

class UpdateMainData
{
    public function __construct(
        private ContextRepository $contextRepository,
        private AuthGateway $authGateway,
        private UserRepository $userRepository
    ){}

    public function execute(string $sector, string $structure, string $email, string $firstname, string $lastname, string $role, ?string $country, ?string $postalCode)
    {
        $currentUser = $this->authGateway->current();
        $context = $this->contextRepository->getByUser($currentUser->id());

        $user = $this->userRepository->getById($currentUser->id());
        $user->update($email, $firstname, $lastname);
        $user->updateRole($role);

        $context->update([
            'sector' => $sector,
            'country' => $country,
            'postal_code' => $postalCode,
            'structure' => $structure,
        ], $currentUser->id());
    }
}
