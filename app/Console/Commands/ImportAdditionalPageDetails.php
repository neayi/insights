<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\LocalesConfig;
use App\Src\UseCases\Infra\Sql\Model\PageModel;
use App\Src\WikiClient;
use GuzzleHttp\Exception\ClientException;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class ImportAdditionalPageDetails extends Command
{
    protected $signature = 'pages:import-additional-page-detail';

    protected $description = 'Import the page type and icon for each page of the wiki';

    public function handle(): void
    {
        $localesConfig = LocalesConfig::all();

        foreach ($localesConfig as $localeConfig) {
            $wikiClient = new WikiClient($localeConfig->toArray());

            /*
             * ############################
             * Récupération du type de page
             * ############################
             */

            $pageTypeQuery = '[[A un type de page::+]]|?A un type de page=pagetype';
            $continue = null;

            do {
                $this->info(sprintf('Importing page types from wiki %s with continue "%s"', $localeConfig->code, (string) $continue));

                $query = $pageTypeQuery;
                if ($continue !== null) {
                    $query .= '|offset=' . $continue;
                }

                $this->info(sprintf('Query: %s', $query));

                $content = $wikiClient->ask($query);

                $this->handlePageTypes($content['query']['results'] ?? [], $localeConfig->code);

                $newContinue = $content['query-continue-offset'] ?? null;
                if ($newContinue !== null && $newContinue > $continue) {
                    $continue = $newContinue;
                } else {
                    $continue = null;
                }
            } while ($continue !== null);

            /*
             * ###############################
             * Récupération de l'icône de page
             * ###############################
             */

            $pageIconQuery = '[[A un glyph::+]]|?A un glyph=pageicon';
            $continue = null;

            do {
                $this->info(sprintf('Importing page icons from wiki %s with continue "%s"', $localeConfig->code, (string) $continue));

                $query = $pageIconQuery;
                if ($continue !== null) {
                    $query .= '|offset=' . $continue;
                }

                $this->info(sprintf('Query: %s', $query));

                $content = $wikiClient->ask($query);

                $this->handlePageIcons($content['query']['results'] ?? [], $localeConfig->code);

                $newContinue = $content['query-continue-offset'] ?? null;
                if ($newContinue !== null && $newContinue > $continue) {
                    $continue = $newContinue;
                } else {
                    $continue = null;
                }
            } while ($continue !== null);
        }

        // TODO: Adds is_tag flag ici
    }

    private function handlePageTypes(array $pages, string $wikiCode)
    {
        foreach ($pages as $pageItem) {
            $title = key($pageItem);
            $page = last($pageItem);
            $typePage = last($page['printouts']['pagetype']);

            $pageModel = PageModel::query()->where('title', $title)->where('wiki', $wikiCode)->first();
            if (!isset($pageModel)) {
                $this->info('Page not found :  '.$title);
                continue;
            }

            $pageModel->type = $typePage ?? null;
            $pageModel->save();
        }
    }

    private function handlePageIcons(array $pages, string $wikiCode)
    {
        foreach ($pages as $pageItem) {
            $title = key($pageItem);
            $page = last($pageItem);
            $icon = last($page['printouts']['pageicon']);

            $pageModel = PageModel::query()->where('title', $title)->where('wiki', $wikiCode)->first();
            if (!isset($pageModel)) {
                $this->info('Page not found :  '.$title);
                continue;
            }

            if ($icon !== false) {
                $pageModel->icon = $icon;
            }

            $pageModel->save();
        }
    }
}
