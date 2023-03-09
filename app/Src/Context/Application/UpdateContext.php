<?php


namespace App\Src\Context\Application;


use App\Src\Context\Domain\ContextRepository;
use App\Src\Shared\Gateway\AuthGateway;
use App\Src\UseCases\Domain\Ports\UserRepository;
use App\Src\UseCases\Domain\System\GetDepartmentFromPostalCode;

class UpdateContext
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

        $geoData = $this->getGeoData($postalCode);

        $context->update([
            'postal_code' => $postalCode,
            'sector' => $sector,
            'structure' => $structure,
            'coordinates' => $geoData['coordinates'],
            'department_number' => $geoData['department_number'],
        ], $currentUser->id());
    }

    private function getGeoData(string $postalCode): array
    {
        return app(GetDepartmentFromPostalCode::class)->execute($postalCode);
    }
}
