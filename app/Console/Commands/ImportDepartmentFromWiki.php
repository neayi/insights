<?php

namespace App\Console\Commands;

use App\Src\UseCases\Domain\Context\Model\Characteristic;
use App\Src\UseCases\Infra\Sql\Model\CharacteristicsModel;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use Ramsey\Uuid\Uuid;

class ImportDepartmentFromWiki extends Command
{
    protected $signature = 'characteristics:department';
    protected $description = 'Import the department';
    private $httpClient;

    private $query = "?action=ask&api_version=3&query=[[A un numéro de département::%2B]] |?A un numéro de département |?A un nom |?A un climat |?A une icone&format=json";
    private $queryPage2 = "?action=ask&api_version=3&query=[[A un numéro de département::%2B]] |?A un numéro de département |?A un nom |?A un climat |?A une icone|offset=50&format=json";
    private $queryPage3 = "?action=ask&api_version=3&query=[[A un numéro de département::%2B]] |?A un numéro de département |?A un nom |?A un climat |?A une icone|offset=100&format=json";


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
        foreach($departments as $key => $department){
            $department = last($department)['printouts'];

            $climat = last($department['A un climat'])['fulltext'];
            $number = last($department['A un numéro de département']);
            $name = last($department['A un nom']);
            $iconUrl = last($department['A une icone'])['fullurl'];

            $characteristicModel = CharacteristicsModel::query()->where('code', $number)->first();
            if(!isset($characteristicModel)){
                $characteristicModel = new CharacteristicsModel();
            }

            $characteristicModel->uuid = $uuid = Uuid::uuid4();
            $characteristicModel->priority = 10000;
            $characteristicModel->page_label = $name;
            $characteristicModel->pretty_page_label = $name;
            $characteristicModel->type = Characteristic::DEPARTMENT;
            $characteristicModel->code = $number;
            $characteristicModel->opt = json_encode([
                'number' => $number,
                'climat' => $climat,
                'url' => 'wiki/'.str_replace(' ', '_', $climat)
            ]);

            try {
                $response = $this->httpClient->get($iconUrl);
                $content = $response->getBody()->getContents();
                $path = 'public/characteristics/' . $uuid . '.png';
                Storage::put('public/characteristics/' . $uuid . '.png', $content);
            }catch (ClientException $e){
                $path = '';
            }

            $characteristicModel->icon = $path;
            $characteristicModel->visible = false;

            $characteristicModel->save();
        }

    }
}
