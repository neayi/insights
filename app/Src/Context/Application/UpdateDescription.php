<?php


namespace App\Src\Context\Application;


use App\Src\Context\Domain\ContextRepository;
use App\Src\Shared\Gateway\AuthGateway;

class UpdateDescription
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

    public function execute(string $description)
    {
        $currentUser = $this->authGateway->current();
        $context = $this->contextRepository->getByUser($currentUser->id());
        $context->update(['description' => $description]);
        $this->contextRepository->update($context, $currentUser->id());
    }
}