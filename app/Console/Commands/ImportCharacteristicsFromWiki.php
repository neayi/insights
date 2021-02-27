<?php

namespace App\Console\Commands;

use App\Src\UseCases\Domain\Agricultural\Dto\GetFarmingType;
use App\Src\UseCases\Infra\Sql\Model\CharacteristicsModel;
use GuzzleHttp\Client;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use Ramsey\Uuid\Uuid;


class ImportCharacteristicsFromWiki extends Command
{
    protected $signature = 'characteristics:import';

    protected $description = 'Import the wiki characteristics';

    private $httpClient;
    private $queryFarming = "?action=ask&api_version=3&query=[[Est un élément de profil::Production]]|?A un fichier d'icone de caractéristique|?Doit être affiché par défaut|?A une priorité d'affichage|?A un label|sort=A une priorité d'affichage|order=asc&format=json";
    private $queryCroppingSystem  = "?action=ask&api_version=3&query=[[Est%20un%20%C3%A9l%C3%A9ment%20de%20profil::Cahier des charges]]|%3FA%20un%20fichier%20d%27icone%20de%20caract%C3%A9ristique|%3FDoit%20%C3%AAtre%20affich%C3%A9%20par%20d%C3%A9faut|%3FA%20une%20priorit%C3%A9%20d%27affichage|%3FA%20un%20label|sort%3DA%20une%20priorit%C3%A9%20d%27affichage|order%3Dasc&format=json";


    public function __construct()
    {
        parent::__construct();
        $this->httpClient = new Client();
    }


    public function handle()
    {
        $this->importCharacteristics($this->queryFarming, GetFarmingType::type);
        $this->importCharacteristics($this->queryCroppingSystem, GetFarmingType::typeSystem);
    }

    public function importCharacteristics(string $query, string $type)
    {
        $queryPictures = '?action=query&format=json&prop=imageinfo&iiprop=url&titles=';
        $this->queryPages = $queryPages = '?action=query&prop=info&format=json&titles=';

        $response = $this->httpClient->get(config('wiki.api_uri').$query);
        $content = json_decode($response->getBody()->getContents(), true);
        $characteristics = $content['query']['results'];

        foreach ($characteristics as $key => $characteristic){
            $page = key($characteristic);
            $characteristic = last($characteristic);

            $uuid = Uuid::uuid4();
            $path = '';
            if(isset($characteristic['printouts']['A un fichier d\'icone de caractéristique'][0]['fulltext'])) {
                $picture = $characteristic['printouts']['A un fichier d\'icone de caractéristique'][0]['fulltext'];
                $picturesApiUri = config('wiki.api_uri').$queryPictures.$picture;

                $response = $this->httpClient->get($picturesApiUri);
                $content = json_decode($response->getBody()->getContents(), true);
                $picturesInfo = $content['query']['pages'];
                foreach($picturesInfo as $picture) {
                    if (isset(last($picture['imageinfo'])['url'])) {
                        $response = $this->httpClient->get(last($picture['imageinfo'])['url']);
                        $content = $response->getBody()->getContents();
                        $path = 'public/characteristics/'.$uuid .'.png';
                        Storage::put('public/characteristics/' . $uuid . '.png', $content);
                    }
                }
            }

            $pagesApiUri = config('wiki.api_uri').$queryPages.$page;
            $response = $this->httpClient->get($pagesApiUri);
            $content = json_decode($response->getBody()->getContents(), true);

            $pageInfo = last($content['query']['pages']);

            $main = last($characteristic['printouts']['Doit être affiché par défaut']) == "t" ? true : false;
            $label = last($characteristic['printouts']['A un label']) !== false ? last($characteristic['printouts']['A un label']) : $pageInfo['title'];

            $characteristicsToSave = [
                'uuid' => $uuid,
                'main' => $main,
                'priority' => (int)last($characteristic['printouts']['A une priorité d\'affichage']),
                'icon' => $path,
                'page_label' => $label,
                'page_id' => (int)$pageInfo['pageid'],
                'type' => $type,
                'code' => $pageInfo['title']
            ];

            $model = CharacteristicsModel::where('page_id', (int)$pageInfo['pageid'])->first();
            if(!isset($model)) {
                $model = new CharacteristicsModel();
            }
            $model->fill($characteristicsToSave);
            $model->save();
        }
    }

}
