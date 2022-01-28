<?php


namespace Tests\Adapters\Repositories;

use App\Src\Insights\Insights\Domain\Organizations\Invitation;
use App\Src\Insights\Insights\Domain\Ports\InvitationRepository;

class InMemoryInvitationRepository implements InvitationRepository
{
    private $invitations = [];

    public function add(Invitation $i)
    {
        $this->invitations[] = $i;
    }

    public function getByHash(string $hash):?Invitation
    {
        foreach ($this->invitations as $invitation){
            if($invitation->hash() === $hash){
                return $invitation;
            }
        }
        return null;
    }
}
