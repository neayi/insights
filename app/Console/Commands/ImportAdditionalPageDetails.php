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
        Storage::makeDirectory('public/pages');

        foreach ($localesConfig as $localeConfig) {
            $this->info(sprintf("Importing Pages with icons and types from wiki %s", $localeConfig->code));
            $wikiClient = new WikiClient($localeConfig->toArray());

            $content = $wikiClient->getPagesAdditionalDetail();
            $pages = $content['query']['results'];

            $this->handlePages($pages, $wikiClient);
            $continue = $content['query-continue-offset'] ?? null;

            while ($continue !== null && $continue !== '') {
                $this->info($continue);
                $content = $wikiClient->getPagesAdditionalDetail($continue);
                $this->handlePages($pages);
                $continue = $content['query-continue-offset'] ?? null;
            }
        }
    }

    private function handlePages($pages)
    {
        foreach ($pages as $page) {
            $title = key($page);
            $page = last($page);
            $typePage = last($page['printouts']['A un type de page']);
            $icon = last($page['printouts']['A un glyph']);

            $pageModel = PageModel::query()->where('title', $title)->first();
            if (!isset($pageModel)) {
                $this->info('Page not found :  '.$title);
                continue;
            }

            if ($icon !== false) {
                $pageModel->icon = $icon;
            }

            $pageModel->type = $typePage !== false ? $typePage : '';
            $pageModel->save();
        }
    }
}
