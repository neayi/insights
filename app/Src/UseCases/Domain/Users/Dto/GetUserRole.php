<?php


namespace App\Src\UseCases\Domain\Users\Dto;

class GetUserRole
{
    public function get()
    {
         return collect([
            new WikiUserRole('advisor'),
            new WikiUserRole('farmer'),
            new WikiUserRole('student'),
            new WikiUserRole('searcher'),
            new WikiUserRole('agro-supplier'),
            new WikiUserRole('others'),
        ]);
    }
}
