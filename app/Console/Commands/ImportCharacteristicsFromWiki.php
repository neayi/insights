<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\LocalesConfig;
use App\Src\UseCases\Domain\Context\Model\Characteristic;
use App\Src\UseCases\Domain\Forum\CharacteristicsForumSyncer;
use App\Src\UseCases\Infra\Sql\Model\CharacteristicsModel;
use App\Src\WikiClient;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Console\Command;
use Ramsey\Uuid\Uuid;


class ImportCharacteristicsFromWiki extends Command
{
    protected $signature = 'characteristics:import';

    protected $description = 'Import the wiki characteristics';

    public function __construct(
        private CharacteristicsForumSyncer $forumSyncer,
    ) {
        parent::__construct();
    }

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
            $queryFarming = "[[Est un élément de profil::Production]]|?A un glyph|?Doit être affiché par défaut|?A une priorité d'affichage|?A un label|sort=A une priorité d'affichage|order=asc";
            $this->importCharacteristics($queryFarming, Characteristic::FARMING_TYPE, $client, $wikiCode);

            $queryCropping = "[[Est un élément de profil::Cahier des charges]]|?A un glyph|?Doit être affiché par défaut|?A une priorité d'affichage|?A un label|sort=A une priorité d'affichage|order=asc";
            $this->importCharacteristics($queryCropping, Characteristic::CROPPING_SYSTEM, $client, $wikiCode);
        }
    }

    /**
     * @throws GuzzleException
     */
    private function importCharacteristics(string $query, string $type, WikiClient $wikiClient, string $wikiCode): void
    {
        $this->info(sprintf("Importing Characteristics for %s", $type));

        $content = $wikiClient->ask($query);
        $characteristics = $content['query']['results'];

        $characteristicsGroupToForum = [];

        foreach ($characteristics as  $characteristic){
            $page = key($characteristic);
            $this->info(sprintf("Importing %s", $page));

            $characteristic = last($characteristic);

            $icon = '';
            $uuid = Uuid::uuid4();
            if(isset($characteristic['printouts']['A un glyph'][0])) {
                $icon = $characteristic['printouts']['A un glyph'][0];
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
                'icon' => $icon,
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

            // Creates or updates forum matching tag
            $characteristicsGroupToForum[] = $model->toDomain();

            $this->info(sprintf("Saving characteristic for %s", $label));
        }

        $this->forumSyncer->syncCharacteristicTagGroup($type, $wikiCode, $characteristicsGroupToForum);
    }
}
