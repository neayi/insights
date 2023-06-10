<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Src\UseCases\Domain\Context\Model\Characteristic;
use App\Src\UseCases\Infra\Sql\Model\CharacteristicsModel;
use App\Src\WikiClient;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use Ramsey\Uuid\Uuid;


class ImportCharacteristicsFromWiki extends Command
{
    protected $signature = 'characteristics:import {country_code}';

    protected $description = 'Import the wiki characteristics';

    /**
     * @throws GuzzleException
     */
    public function handle(): void
    {
        // @see https://wiki.tripleperformance.fr/wiki/Aide:Requettes_Insights

        $optFarming = [
            'query' => '[[Est un élément de profil::Production]]|?A un fichier d\'icone de caractéristique|?Doit être affiché par défaut|?A une priorité d\'affichage|?A un label|sort=A une priorité d\'affichage|order=asc',
        ];
        $this->importCharacteristics($optFarming, Characteristic::FARMING_TYPE);

        $optCropping = [
            'query' => "[[Est un élément de profil::Cahier des charges]]|?A un fichier d'icone de caractéristique|?Doit être affiché par défaut|?A une priorité d'affichage|?A un label|sort=A une priorité d'affichage|order=asc",
        ];
        $this->importCharacteristics($optCropping, Characteristic::CROPPING_SYSTEM);
    }

    /**
     * @throws GuzzleException
     */
    private function importCharacteristics(array $opt, string $type): void
    {
        $this->info(sprintf("Importing Characteristics for %s", $type));

        $countryCode = $this->argument('country_code');
        $wikiClient = new WikiClient($countryCode);

        $content = $wikiClient->searchCharacteristics($opt);
        $characteristics = $content['query']['results'];

        foreach ($characteristics as  $characteristic){
            $page = key($characteristic);
            $this->info(sprintf("Importing %s", $page));

            $characteristic = last($characteristic);

            $uuid = Uuid::uuid4();
            $path = '';
            if(isset($characteristic['printouts']['A un fichier d\'icone de caractéristique'][0]['fulltext'])) {
                $picture = $characteristic['printouts']['A un fichier d\'icone de caractéristique'][0]['fulltext'];

                $content = $wikiClient->getPictureInfo($picture);

                if (!isset($picturesInfo)) {
                    continue;
                }
                $picturesInfo = $content['query']['pages'];

                foreach($picturesInfo as $picture) {
                    if (isset(last($picture['imageinfo'])['url'])) {
                        try {
                            $content = $wikiClient->downloadPicture(last($picture['imageinfo'])['url']);
                            $path = 'public/characteristics/' . $uuid . '.png';
                            Storage::put('public/characteristics/' . $uuid . '.png', $content);
                        }catch (ClientException $e){
                            $path = '';
                        }
                    }
                }
            }

            $content = $wikiClient->getInfoPage($page);

            $pageInfo = last($content['query']['pages']);
            $main = last($characteristic['printouts']['Doit être affiché par défaut']) == "t";
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
