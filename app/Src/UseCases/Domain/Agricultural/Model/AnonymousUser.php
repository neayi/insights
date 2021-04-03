<?php


namespace App\Src\UseCases\Domain\Agricultural\Model;


use App\Src\UseCases\Domain\Ports\InteractionRepository;

class AnonymousUser implements CanInteract
{
    use Interact;

    private $identifier;
    private $interactionRepository;

    public function __construct(string $identifier)
    {
        $this->identifier = $identifier;
        $this->interactionRepository = app(InteractionRepository::class);
    }

    public function key(): string
    {
        return 'cookie_user_session_id';
    }

    public function identifier():string
    {
        return $this->identifier;
    }


}
