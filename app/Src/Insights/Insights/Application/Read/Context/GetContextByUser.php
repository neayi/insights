<?php


namespace App\Src\Insights\Insights\Application\Read\Context;


use App\Src\UseCases\Domain\Context\Dto\ContextDto;
use App\Src\UseCases\Domain\Ports\ContextRepository;

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
