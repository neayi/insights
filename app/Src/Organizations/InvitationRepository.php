<?php


namespace App\Src\Organizations;



use App\Src\Organizations\Model\Invitation;

interface InvitationRepository
{
    public function add(Invitation $i);
    public function getByHash(string $hash):?Invitation;
}
