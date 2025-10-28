<?php

declare(strict_types=1);

namespace App\Src\UseCases\Domain\Context\Queries;


use App\Src\WikiClient;
use Illuminate\Support\Facades\Auth;

/**
 * Search structure remotely with the wiki api
 */
class SearchStructure
{
    public function execute(string $search):array
    {
        $client = new WikiClient(Auth::user()->default_locale);
        $content = $client->searchStructures($search);
        if(isset($content['query']['search'])){
            $results = array_column($content['query']['search'], 'title');
            return array_map(function ($item){
                return str_replace('Structure:', '', $item);
            }, $results);
        }
        return ['results' => []];
    }
}
