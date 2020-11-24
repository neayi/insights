<?php


namespace App\Src\UseCases\Infra\Sql;


use App\Src\UseCases\Domain\Ports\ListRepository;
use Illuminate\Support\Facades\DB;

class ListRepositorySql implements ListRepository
{
    public function getByType(string $type): array
    {
        $list = DB::table('list')
            ->where('type', $type)
            ->orderBy('priority')
            ->get();
        return $list->toArray();
    }
}
