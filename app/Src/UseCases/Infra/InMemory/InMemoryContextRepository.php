<?php


namespace App\Src\UseCases\Infra\InMemory;


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

}