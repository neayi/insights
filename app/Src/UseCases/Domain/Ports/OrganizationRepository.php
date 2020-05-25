<?php


namespace App\Src\UseCases\Domain\Ports;


use App\Src\UseCases\Domain\Organization;

interface OrganizationRepository
{
    public function get(string $id):? Organization;
    public function add(Organization $o);
}
