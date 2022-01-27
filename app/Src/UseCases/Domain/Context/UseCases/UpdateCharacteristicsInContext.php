<?php


namespace App\Src\UseCases\Domain\Context\UseCases;


use App\Src\UseCases\Domain\Ports\ContextRepository;
use App\Src\UseCases\Domain\Shared\Gateway\AuthGateway;

class UpdateCharacteristicsInContext
{
    private $contextRepository;
    private $authGateway;

    public function __construct(
        ContextRepository $contextRepository,
        AuthGateway $authGateway
    )
    {
        $this->contextRepository = $contextRepository;
        $this->authGateway = $authGateway;
    }

    public function execute(array $characteristics)
    {
        $currentUser = $this->authGateway->current();
        $context = $this->contextRepository->getByUser($currentUser->id());
        $context->update(['characteristics' => $characteristics], $currentUser->id());
    }
}
