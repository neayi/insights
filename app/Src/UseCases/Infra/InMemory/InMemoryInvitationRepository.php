<?php


namespace App\Src\UseCases\Infra\InMemory;


use App\Src\UseCases\Domain\Invitation;
use App\Src\UseCases\Domain\Ports\InvitationRepository;

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
