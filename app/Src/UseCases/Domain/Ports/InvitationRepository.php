<?php


namespace App\Src\UseCases\Domain\Ports;




use App\Src\Insights\Insights\Domain\Organizations\Invitation;

interface InvitationRepository
{
    public function add(Invitation $i);
    public function getByHash(string $hash):?Invitation;
}
