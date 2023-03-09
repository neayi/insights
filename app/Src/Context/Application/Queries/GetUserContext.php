<?php


namespace App\Src\Context\Application\Queries;


use App\Src\Context\Application\Dto\ContextDto;
use App\Src\Context\Domain\ContextRepository;

class GetUserContext
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
