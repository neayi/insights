<?php


namespace App\Src\UseCases\Domain\Context\Queries;


use GuzzleHttp\Client;

/**
 * Search structure remotely with the wiki api
 */
class SearchStructure
{
    public function execute(string $search):array
    {
        $client = new Client();
        $uri = config('wiki.api_uri').'?action=query&list=search&srwhat=text&srsearch='.$search.'&srqiprofile=classic_noboostlinks&srnamespace=3000&format=json';
        try {
            $response = $client->get($uri);
        }catch (\Throwable $e){
            return ['results' => []];
        }
        $content = json_decode($response->getBody()->getContents(), true);
        if(isset($content['query']['search'])){
            $results = array_column($content['query']['search'], 'title');
            return array_map(function ($item){
                return str_replace('Structure:', '', $item);
            }, $results);
        }
        return ['results' => []];
    }
}
