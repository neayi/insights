<?php


namespace App\Src\UseCases\Infra\Sql;


use App\Src\UseCases\Domain\Organizations\Model\Invitation;
use App\Src\UseCases\Domain\Ports\InvitationRepository;
use Illuminate\Support\Facades\DB;

class InvitationRepositorySql implements InvitationRepository
{
    public function add(Invitation $i)
    {
        list($organizationId, $email, $firstname, $lastname) = $i->data();
        DB::table('invitations')->insert([
            'organization_id' => $organizationId,
            'email' => $email,
            'firstname' => $firstname,
            'lastname' => $lastname,
            'send_at' => (new \DateTime())->format('Y-m-d H:i:s'),
            'hash' => $i->hash()
        ]);
    }

    public function getByHash(string $hash): ?Invitation
    {
        $record = DB::table('invitations')
            ->select()
            ->where('hash', $hash)
            ->first();
        if(!isset($record)){
            return null;
        }
        return new Invitation($record->organization_id, $record->email, $record->firstname, $record->lastname);
    }
}
