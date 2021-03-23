<?php


namespace Tests\Adapters\Repositories;


use App\Src\UseCases\Domain\Agricultural\Dto\ContextDto;
use App\Src\UseCases\Domain\Agricultural\Model\Context;
use App\Src\UseCases\Domain\Ports\ContextRepository;

class InMemoryContextRepository implements ContextRepository
{
    private $exploitations = [];

    public function add(Context $exploitation, string $userId)
    {
        $this->exploitations[$userId] = $exploitation;
    }

    public function getByUser(string $userId)
    {
        return $this->exploitations[$userId] ?? null;
    }

    public function getByUserDto(string $userId): ?ContextDto
    {
        // TODO: Implement getByUserDto() method.
    }

}
