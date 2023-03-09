<?php


namespace Tests\Adapters\Repositories;


use App\Src\Context\Application\Dto\ContextDto;
use App\Src\Context\Domain\Context;
use App\Src\Context\Domain\ContextRepository;

class InMemoryContextRepository implements ContextRepository
{
    private $contexts = [];

    public function add(Context $context, string $userId)
    {
        $this->contexts[$userId] = $context;
    }

    public function update(Context $context, string $userId)
    {
        $this->contexts[$userId] = $context;
    }

    public function getByUser(string $userId):?Context
    {
        return $this->contexts[$userId] ?? null;
    }

    public function getByUserDto(string $userId): ?ContextDto
    {
        // TODO: Implement getByUserDto() method.
    }

}
