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


class ImportCharacteristicsFromWiki extends Command
{
    protected $signature = 'characteristics:import {country_code}';

    protected $description = 'Import the wiki characteristics';

    private Client $httpClient;

    // @see https://wiki.tripleperformance.fr/wiki/Aide:Requettes_Insights
    private string $queryFarming = "?action=ask&api_version=3&query=[[Est un élément de profil::Production]]|?A un fichier d'icone de caractéristique|?Doit être affiché par défaut|?A une priorité d'affichage|?A un label|sort=A une priorité d'affichage|order=asc&format=json";
    private string $queryCroppingSystem  = "?action=ask&api_version=3&query=[[Est un élément de profil::Cahier des charges]]|?A un fichier d'icone de caractéristique|?Doit être affiché par défaut|?A une priorité d'affichage|?A un label|sort=A une priorité d'affichage|order=asc&format=json";


    public function __construct()
    {
        parent::__construct();
        $this->httpClient = new Client();
    }


    public function handle()
    {
        $this->importCharacteristics($this->queryFarming, Characteristic::FARMING_TYPE);
        $this->importCharacteristics($this->queryCroppingSystem, Characteristic::CROPPING_SYSTEM);
    }

    public function importCharacteristics(string $query, string $type)
    {
        $queryPictures = '?action=query&redirects=true&format=json&prop=imageinfo&iiprop=url&titles=';
        $queryPages = '?action=query&redirects=true&prop=info&format=json&titles=';

        $countryCode = $this->argument('country_code');
        $baseUri = config(sprintf('wiki.api_uri_%s', $countryCode));

        $response = $this->httpClient->get($baseUri.$query);
        $content = json_decode($response->getBody()->getContents(), true);
        $characteristics = $content['query']['results'];

        foreach ($characteristics as $key => $characteristic){
            $page = key($characteristic);
            $characteristic = last($characteristic);

            $uuid = Uuid::uuid4();
            $path = '';
            if(isset($characteristic['printouts']['A un fichier d\'icone de caractéristique'][0]['fulltext'])) {
                $picture = $characteristic['printouts']['A un fichier d\'icone de caractéristique'][0]['fulltext'];
                $picturesApiUri = $baseUri.$queryPictures.$picture;

                $response = $this->httpClient->get($picturesApiUri);
                $content = json_decode($response->getBody()->getContents(), true);
                $picturesInfo = $content['query']['pages'];
                foreach($picturesInfo as $picture) {
                    if (isset(last($picture['imageinfo'])['url'])) {
                        try {
                            $response = $this->httpClient->get(last($picture['imageinfo'])['url']);
                            $content = $response->getBody()->getContents();
                            $path = 'public/characteristics/' . $uuid . '.png';
                            Storage::put('public/characteristics/' . $uuid . '.png', $content);
                        }catch (ClientException $e){
                            $path = '';
                        }
                    }
                }
            }

            $pagesApiUri = $baseUri.$queryPages.$page;
            $response = $this->httpClient->get($pagesApiUri);
            $content = json_decode($response->getBody()->getContents(), true);

            $pageInfo = last($content['query']['pages']);

            $main = last($characteristic['printouts']['Doit être affiché par défaut']) == "t" ? true : false;
            $label = $page;
            $prettyPage = last($characteristic['printouts']['A un label']) !== false ? last($characteristic['printouts']['A un label']) : $pageInfo['title'];

            $characteristicsToSave = [
                'uuid' => $uuid,
                'main' => $main,
                'priority' => (int)last($characteristic['printouts']['A une priorité d\'affichage']),
                'icon' => $path,
                'page_label' => $label,
                'pretty_page_label' => $prettyPage,
                'page_id' => (int)$pageInfo['pageid'],
                'type' => $type,
                'code' => $pageInfo['title'],
                'country_code' => $countryCode,
            ];

            $model = CharacteristicsModel::query()
                ->where('page_id', (int)$pageInfo['pageid'])
                ->where('country_code', $countryCode)
                ->first();
            if(!isset($model)) {
                $model = new CharacteristicsModel();
            }
            $model->fill($characteristicsToSave);
            $model->save();
        }
    }

}
