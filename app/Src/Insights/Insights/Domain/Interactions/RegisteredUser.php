<?php


namespace App\Src\Insights\Insights\Domain\Interactions;


use App\Src\UseCases\Domain\Ports\InteractionRepository;

class RegisteredUser implements CanInteract
{
    use Interact;

    private $userId;
    private $interactionRepository;

    public function __construct(string $userId)
    {
        $this->userId = $userId;
        $this->interactionRepository = app(InteractionRepository::class);
    }

    public function key(): string
    {
        return 'user_id';
    }

    public function identifier():string
    {
        return $this->userId;
    }
}
