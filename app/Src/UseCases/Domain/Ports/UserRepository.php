<?php


namespace App\Src\UseCases\Domain\Ports;


use App\Src\UseCases\Domain\User;

interface UserRepository
{
    public function getByEmail(string $email):? User;
    public function getById(string $id):?User;
    public function search(string $organizationId, int $page, int $perPage = 10): array;
    public function add(User $u, string $password = null);
    public function update(User $u);
    public function delete(string $userId);
}
