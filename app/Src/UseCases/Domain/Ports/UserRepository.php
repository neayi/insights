<?php


namespace App\Src\UseCases\Domain\Ports;


use App\Src\UseCases\Domain\User;
use App\Src\UseCases\Domain\Users\Stats;

interface UserRepository
{
    public function getByEmail(string $email):? User;
    public function getById(string $id):?User;
    public function getByProvider(string $provider, string $providerId):?User;
    public function search(string $organizationId, int $page, int $perPage = 10): array;
    public function add(User $u, string $password = null);
    public function update(User $u);
    public function delete(string $userId);
    public function getAdminOfOrganization(string $organizationId):array;
    public function getStats(string $userId):Stats;
    public function addStats(string $userId, Stats $stats);
}
