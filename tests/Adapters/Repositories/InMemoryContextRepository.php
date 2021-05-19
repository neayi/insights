<?php


namespace Tests\Adapters\Repositories;


use App\Src\UseCases\Domain\Context\Dto\ContextDto;
use App\Src\UseCases\Domain\Context\Model\Context;
use App\Src\UseCases\Domain\Ports\ContextRepository;

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

    public function getByUser(string $userId)
    {
        return $this->contexts[$userId] ?? null;
    }

    public function getByUserDto(string $userId): ?ContextDto
    {
        // TODO: Implement getByUserDto() method.
    }

}
