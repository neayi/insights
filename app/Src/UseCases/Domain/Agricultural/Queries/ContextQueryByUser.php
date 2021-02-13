<?php


namespace App\Src\UseCases\Domain\Agricultural\Queries;


use App\Src\UseCases\Domain\Agricultural\Dto\ContextDto;
use App\Src\UseCases\Domain\Ports\ContextRepository;

class ContextQueryByUser
{
    private $contextRepository;

    public function __construct(ContextRepository $contextRepository)
    {
        $this->contextRepository = $contextRepository;
    }

    public function execute(string $userId):?ContextDto
    {
        return $this->contextRepository->getByUserDto($userId);
    }
}
