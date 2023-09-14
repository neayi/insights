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

    public function execute(string $postalCode, string $sector, string $structure, string $email, string $firstname, string $lastname, string $role, array $geo = [])
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
            'coordinates' => !empty($geo['coordinates']) ? array_reverse($geo['coordinates']) : [],
            'country_code' => !empty($geo['country_code']) ? $geo['country_code'] : null,
        ], $currentUser->id());
    }
}
