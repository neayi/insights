<?php


namespace Tests\Adapters\Repositories;

use App\Src\Insights\Insights\Domain\Organizations\Organization;
use App\Src\Insights\Insights\Domain\Ports\OrganizationRepository;

class InMemoryOrganizationRepository implements OrganizationRepository
{
    private $organizations = [];

    public function get(string $id):? Organization
    {
        foreach ($this->organizations as $organization){
            if($organization->id() === $id){
                return $organization;
            }
        }
        return null;
    }

    public function add(Organization $o)
    {
        $this->organizations[] = $o;
    }

    public function search(int $page, int $perPage = 10): array
    {
        $chunks = array_chunk($this->organizations, $perPage);
        $list = isset($chunks[$page-1]) ? $chunks[$page-1] : [];
        return [
            'list' => $list,
            'total' => count($this->organizations)
        ];
    }

    public function update(Organization $o)
    {
        foreach ($this->organizations as $key => $organization){
            if($organization->id() === $o->id()){
                $this->organizations[$key] = $organization;
            }
        }
    }


}
