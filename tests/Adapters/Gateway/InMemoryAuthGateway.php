<?php


namespace Tests\Adapters\Gateway;


use App\Src\Shared\Gateway\AuthGateway;
use App\Src\Users\Domain\User;

class InMemoryAuthGateway implements AuthGateway
{
    private $currentUser = null;
    private $wikiSessionId = null;

    public function log(User $u)
    {
        $this->currentUser = $u;
    }

    public function current(): ?User
    {
        return $this->currentUser;
    }

    public function wikiSessionId():? string
    {
        return $this->wikiSessionId;
    }

    public function setWikiSessionId(string $sessionId)
    {
        return $this->wikiSessionId = $sessionId;
    }
}
