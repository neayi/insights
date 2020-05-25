<?php


namespace App\Src\UseCases\Infra\Sql;


use App\Src\UseCases\Domain\Address;
use App\Src\UseCases\Domain\Organization;
use App\Src\UseCases\Domain\Ports\OrganizationRepository;
use Illuminate\Support\Facades\DB;

class SqlOrganizationRepository implements OrganizationRepository
{
    public function get(string $id): ?Organization
    {
        $record = DB::table('organizations')
            ->select()
            ->where('uuid', $id)
            ->first();
        if(!isset($record)){
            return null;
        }
        $address = new Address($record->city, $record->address1, $record->address2, $record->postal_code);
        return new Organization($id, $record->name, $record->path_picture, $address);
    }

    public function add(Organization $o)
    {
        DB::table('organizations')->insert($o->toArray());
    }

}
