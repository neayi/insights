<?php


namespace App\Src\UseCases\Domain\Ports;


use App\Src\UseCases\Domain\User;

interface UserRepository
{
    public function getByEmail(string $email):? User;
    public function getById(string $id):?User;
    public function add(User $u);
}
