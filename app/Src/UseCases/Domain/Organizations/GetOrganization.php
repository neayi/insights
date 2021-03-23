<?php


namespace App\Src\UseCases\Domain\Organizations;

use App\Src\UseCases\Domain\Ports\OrganizationRepository;

class GetOrganization
{
    private $organizationRepository;

    public function __construct(OrganizationRepository $organizationRepository)
    {
        $this->organizationRepository = $organizationRepository;
    }

    public function get(string $organizationId)
    {
        return $this->organizationRepository->get($organizationId);
    }
}
