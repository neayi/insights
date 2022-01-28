<?php


namespace App\Src\Insights\Insights\Application\Read\Organizations;

use App\Src\Insights\Insights\Domain\Ports\OrganizationRepository;

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
