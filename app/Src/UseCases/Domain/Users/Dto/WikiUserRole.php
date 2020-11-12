<?php


namespace App\Src\UseCases\Domain\Users\Dto;


use Illuminate\Contracts\Support\Arrayable;

class WikiUserRole implements Role, Arrayable
{
    public $role;

    public function __construct(string $role)
    {
        $this->role = $role;
    }

    public function toArray()
    {
        return [
            'role' => $this->role
        ];
    }
}
