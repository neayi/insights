<?php


namespace App\Src\Organizations;


use App\Src\Organizations\Model\Organization;

interface OrganizationRepository
{
    public function get(string $id):? Organization;
    public function add(Organization $o);
    public function update(Organization $o);
    public function search(int $page, int $perPage = 10): array;
}
