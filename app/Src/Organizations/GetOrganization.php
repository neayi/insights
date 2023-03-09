<?php


namespace App\Src\Organizations;

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
