<?php


namespace App\Src\UseCases\Domain;


use App\Src\UseCases\Domain\Ports\OrganizationRepository;

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
