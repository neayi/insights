<?php


namespace App\Src\Users;


interface UserRepository
{
    public function getByEmail(string $email):? User;
    public function getById(string $id):?User;
    public function getByProvider(string $provider, string $providerId):?User;
    public function search(string $organizationId, int $page, int $perPage = 10): array;
    public function add(User $u, string $password = null);
    public function update(User $u);
    public function updateProviders(User $u);
    public function delete(string $userId);
    public function getAdminOfOrganization(string $organizationId):array;
    public function getStats(string $userId):Stats;
    public function addStats(string $userId, Stats $stats);
    public function verifyEmail(string $userId);
}
