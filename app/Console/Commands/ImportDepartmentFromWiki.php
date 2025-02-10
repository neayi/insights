<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Src\UseCases\Domain\Context\Model\Characteristic;
use App\Src\UseCases\Infra\Sql\Model\CharacteristicsModel;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use Ramsey\Uuid\Uuid;

/**
 * @deprecated
 */
class ImportDepartmentFromWiki extends Command
{
    protected $signature = 'characteristics:department';
    protected $description = 'Import the department';
    private $httpClient;

    private $query = "?action=ask&api_version=3&query=[[A un numéro de département::%2B]] |?A un numéro de département |?A un nom |?A un climat |?A une icone&format=json";
    private $queryPage2 = "?action=ask&api_version=3&query=[[A un numéro de département::%2B]] |?A un numéro de département |?A un nom |?A un climat |?A une icone|offset=50&format=json";
    private $queryPage3 = "?action=ask&api_version=3&query=[[A un numéro de département::%2B]] |?A un numéro de département |?A un nom |?A un climat |?A une icone|offset=100&format=json";

    private $queryPictures = '?action=query&titles=:file:&prop=imageinfo&iiprop=url&format=json';


    public function __construct()
    {
        parent::__construct();
        $this->httpClient = new Client();
    }


    public function handle()
    {
        $response = $this->httpClient->get(config('wiki.api_uri').$this->query);
        $content = json_decode($response->getBody()->getContents(), true);
        $departments = $content['query']['results'];
        $this->handleDepartments($departments);

        $response = $this->httpClient->get(config('wiki.api_uri').$this->queryPage2);
        $content = json_decode($response->getBody()->getContents(), true);
        $departments = $content['query']['results'];
        $this->handleDepartments($departments);

        $response = $this->httpClient->get(config('wiki.api_uri').$this->queryPage3);
        $content = json_decode($response->getBody()->getContents(), true);
        $departments = $content['query']['results'];
        $this->handleDepartments($departments);
    }

    private function handleDepartments($departments)
    {
        foreach($departments as $department){
            $key = key($department);
            $department = last($department)['printouts'];

            $climat = last($department['A un climat'])['fulltext'];
            $number = last($department['A un numéro de département']);
            $name = last($department['A un nom']);
            $iconUrlApi = str_replace(':file:', last($department['A une icone'])['fulltext'], $this->queryPictures);

            $characteristicModel = CharacteristicsModel::query()->where('code', $number)->first();
            if(!isset($characteristicModel)){
                $characteristicModel = new CharacteristicsModel();
            }

            $characteristicModel->uuid = $uuid = Uuid::uuid4();
            $characteristicModel->priority = 10000;
            $characteristicModel->page_label = $key;
            $characteristicModel->pretty_page_label = $name;
            $characteristicModel->type = Characteristic::DEPARTMENT;
            $characteristicModel->code = $number;
            $characteristicModel->opt = [
                'number' => $number,
                'climat' => $climat,
                'url' => 'wiki/'.str_replace(' ', '_', $climat)
            ];


            $response = $this->httpClient->get(config('wiki.api_uri').$iconUrlApi);
            $content = json_decode($response->getBody()->getContents(), true);
            $picturesInfo = $content['query']['pages'];
            foreach($picturesInfo as $picture) {
                if (isset($picture['imageinfo']) && isset(last($picture['imageinfo'])['url'])) {
                    $characteristicModel->page_id = $picture['pageid'];
                    try {
                        $imageURL = last($picture['imageinfo'])['url'];

                        // Force HTTP as we are behind the proxy
                        $imageURL = str_replace('https', 'http', $imageURL);

                        $response = $this->httpClient->get($imageURL);
                        $content = $response->getBody()->getContents();
                        $path = 'public/characteristics/' . $uuid . '.png';
                        Storage::put('public/characteristics/' . $uuid . '.png', $content);
                    }catch (ClientException $e){
                        $this->info('No icon for department : '.$number);
                        $path = '';
                    }
                }else{
                    $this->info('No icon for department : '.$number);
                    $path = '';
                }
            }

            $characteristicModel->icon = $path;
            $characteristicModel->visible = false;

            $characteristicModel->save();
        }
    }
}
