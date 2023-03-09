<?php


namespace App\Src\Context\Application;


use App\Src\Context\Domain\ContextRepository;
use App\Src\Shared\Gateway\AuthGateway;

class UpdateContextCharacteristics
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
