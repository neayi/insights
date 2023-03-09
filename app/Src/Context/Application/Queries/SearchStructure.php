<?php


namespace App\Src\Context\Application\Queries;


use App\Src\Context\Application\Client\WikiClient;

/**
 * Search structure remotely with the wiki api
 */
class SearchStructure
{
    private $wikiClient;

    public function __construct(WikiClient $client)
    {
        $this->wikiClient = $client;
    }

    public function execute(string $search):array
    {
        $content = $this->wikiClient->searchStructures($search);
        if(isset($content['query']['search'])){
            $results = array_column($content['query']['search'], 'title');
            return array_map(function ($item){
                return str_replace('Structure:', '', $item);
            }, $results);
        }
        return $content;
    }
}
