<?php


namespace App\Src\UseCases\Domain\Context\UseCases;


use App\Src\UseCases\Domain\Ports\ContextRepository;
use App\Src\UseCases\Domain\Shared\Gateway\AuthGateway;

class AddCharacteristicsToContext
{
    private $authGateway;
    private $contextRepository;

    public function __construct(
        AuthGateway $authGateway,
        ContextRepository $contextRepository
    )
    {
        $this->authGateway = $authGateway;
        $this->contextRepository = $contextRepository;
    }

    public function execute(array $characteristicsIds)
    {
        $user = $this->authGateway->current();
        $context = $this->contextRepository->getByUser($user->id());
        $context->addCharacteristics($characteristicsIds, $user->id());
    }
}
