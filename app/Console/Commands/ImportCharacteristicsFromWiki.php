<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\LocalesConfig;
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
    protected $signature = 'characteristics:import';

    protected $description = 'Import the wiki characteristics';

    /**
     * @throws GuzzleException
     */
    public function handle(): void
    {
        // @see https://wiki.tripleperformance.fr/wiki/Aide:Requettes_Insights

        $localesConfig = LocalesConfig::all();

        foreach ($localesConfig as $localeConfig) {
            $client = new WikiClient($localeConfig->toArray());
            $wikiCode = $localeConfig->code;
            $optFarming = [
                'query' => "[[Est un élément de profil::Production]]|?A un fichier d'icone de caractéristique|?Doit être affiché par défaut|?A une priorité d'affichage|?A un label|sort=A une priorité d'affichage|order=asc",
            ];
            $this->importCharacteristics($optFarming, Characteristic::FARMING_TYPE, $client, $wikiCode);

            $optCropping = [
                'query' => "[[Est un élément de profil::Cahier des charges]]|?A un fichier d'icone de caractéristique|?Doit être affiché par défaut|?A une priorité d'affichage|?A un label|sort=A une priorité d'affichage|order=asc",
            ];
            $this->importCharacteristics($optCropping, Characteristic::CROPPING_SYSTEM, $client, $wikiCode);
        }
    }

    /**
     * @throws GuzzleException
     */
    private function importCharacteristics(array $opt, string $type, WikiClient $wikiClient, string $wikiCode): void
    {
        $this->info(sprintf("Importing Characteristics for %s", $type));

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

                if (!isset($content)) {
                    continue;
                }
                $picturesInfo = $content['query']['pages'];

                foreach($picturesInfo as $picture) {
                    if (isset($picture['imageinfo']) && isset(last($picture['imageinfo'])['url'])) {
                        try {
                            $imageURL = last($picture['imageinfo'])['url'];
                            
                            // Force HTTP as we are behind the proxy
                            $imageURL = str_replace('https', 'http', $imageURL);

                            $content = $wikiClient->downloadPicture($imageURL);
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
                'wiki' => strtolower($wikiCode),
            ];

            $model = CharacteristicsModel::query()
                ->where('page_id', (int)$pageInfo['pageid'])
                ->where('wiki', $wikiCode)
                ->first();
            if(!isset($model)) {
                $model = new CharacteristicsModel();
            }
            $model->fill($characteristicsToSave);
            $model->save();
            $this->info(sprintf("Saving characteristic for %s", $label));

        }
    }

}
