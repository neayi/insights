<?php


namespace App\Src\UseCases\Domain\Ports;


use App\Src\UseCases\Domain\Organization;

interface OrganizationRepository
{
    public function get(string $id):? Organization;
    public function add(Organization $o);
    public function update(Organization $o);
    public function search(int $page, int $perPage = 10): array;
}
