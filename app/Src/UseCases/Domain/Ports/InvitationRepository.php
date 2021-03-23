<?php


namespace App\Src\UseCases\Domain\Ports;



use App\Src\UseCases\Domain\Organizations\Model\Invitation;

interface InvitationRepository
{
    public function add(Invitation $i);
    public function getByHash(string $hash):?Invitation;
}
