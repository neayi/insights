<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\LocalesConfig;
use App\Src\UseCases\Infra\Sql\Model\PageModel;
use App\Src\WikiClient;
use GuzzleHttp\Exception\ClientException;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class ImportPagesWithIconAndTypeFromWiki extends Command
{
    protected $signature = 'pages:import-with-icons-type';

    protected $description = 'Import pages with icons and types from the wiki';

    public function handle()
    {
        $localesConfig = LocalesConfig::all();
        Storage::makeDirectory('public/pages');

        foreach ($localesConfig as $localeConfig) {
            $this->info(sprintf("Importing Pages with icons and types from wiki %s", $localeConfig->code));
            $wikiClient = new WikiClient($localeConfig->toArray());

            $content = $wikiClient->searchPagesLinkedToCharacteristics();
            $pages = $content['query']['results'];

            $this->handlePages($pages, $wikiClient);
            $continue = $content['query-continue-offset'] ?? null;

            while ($continue !== null && $continue !== '') {
                $this->info($continue);
                $content = $wikiClient->searchPagesLinkedToCharacteristics($continue);
                $this->handlePages($pages, $wikiClient);
                $continue = $content['query-continue-offset'] ?? null;
            }
        }
    }

    private function handlePages($pages, WikiClient $wikiClient)
    {
        foreach ($pages as $page) {
            $title = key($page);
            $page = last($page);
            $typePage = last($page['printouts']['A un type de page']);
            $icon = last($page['printouts']['A un fichier d\'icone de caractéristique']);

            $pageModel = PageModel::where('title', $title)->first();
            if (!isset($pageModel)) {
                $this->info('Page not found :  '.$title);
                continue;
            }

            if ($icon !== false) {
                $content = $wikiClient->getPictureInfo($icon['fulltext']);
                $picturesInfo = $content['query']['pages'];
                foreach($picturesInfo as $picture) {
                    if (isset($picture['imageinfo']) && isset(last($picture['imageinfo'])['url'])) {
                        try {
                            $content = $wikiClient->downloadPicture(last($picture['imageinfo'])['url']);
                            $path = 'public/pages/' . $pageModel->id . '.png';
                            Storage::put('public/pages/' . $pageModel->id . '.png', $content);
                        } catch (ClientException $e){
                            $path = '';
                        }
                    } else{
                        $path = '';
                    }
                }
                $pageModel->icon = $path;
            }

            $pageModel->type = $typePage !== false ? $typePage : '';
            $pageModel->save();
        }
    }
}
