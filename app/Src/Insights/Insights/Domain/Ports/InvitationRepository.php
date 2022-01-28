<?php


namespace App\Src\Insights\Insights\Domain\Ports;




use App\Src\Insights\Insights\Domain\Organizations\Invitation;

interface InvitationRepository
{
    public function add(Invitation $i);
    public function getByHash(string $hash):?Invitation;
}
