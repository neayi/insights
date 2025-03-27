<?php


declare(strict_types=1);

namespace App\Src\UseCases\Domain\Context\UseCases;


use App\Src\UseCases\Domain\Ports\ContextRepository;
use App\Src\UseCases\Domain\Ports\UserRepository;
use App\Src\UseCases\Domain\Shared\Gateway\AuthGateway;
use App\Src\UseCases\Domain\System\GetDepartmentFromPostalCode;

class UpdateMainData
{
    public function __construct(
        private ContextRepository $contextRepository,
        private AuthGateway $authGateway,
        private UserRepository $userRepository
    ){}

    public function execute(string $postalCode, string $sector, string $structure, string $email, string $firstname, string $lastname, string $role, string $country)
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
            // 'coordinates' => !empty($geo['coordinates']) ? array_reverse($geo['coordinates']) : [],
        ], $currentUser->id());
    }
}
