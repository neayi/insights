<?php


namespace App\Src\Insights\Insights\Application\Read\Context;


use App\Src\Insights\Insights\Domain\Ports\ContextRepository;
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
