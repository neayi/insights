<?php


namespace App\Src\Organizations;


class ListOrganizations
{
    private $organizationRepository;

    public function __construct(OrganizationRepository $organizationRepository)
    {
        $this->organizationRepository = $organizationRepository;
    }

    public function list(int $page, int $perPage = 10)
    {
        return $this->organizationRepository->search($page, $perPage);
    }
}
