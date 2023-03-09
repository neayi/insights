<?php


namespace App\Src\UseCases\Domain\Context\Queries;


use App\Src\Context\Domain\ContextRepository;
use App\Src\UseCases\Domain\Context\Dto\ContextDto;

class GetContextByUser
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
