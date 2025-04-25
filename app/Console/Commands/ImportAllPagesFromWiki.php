<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\LocalesConfig;
use App\Src\UseCases\Infra\Sql\Model\PageModel;
use App\Src\WikiClient;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Console\Command;

class ImportAllPagesFromWiki extends Command
{
    protected $signature = 'pages:import-all';

    protected $description = 'Import all pages from the wikis as configured in the  locale configs';

    public function handle(): void
    {
        $localesConfig = LocalesConfig::all();

        foreach ($localesConfig as $localeConfig) {
            try {
                $this->handleImport($localeConfig);
            } catch (\Throwable $e) {
                $this->error(get_class($e));
                $this->error(sprintf('Error importing pages from wiki %s: %s', $localeConfig->code, $e->getMessage()));
            }
        }
    }

    private function handlePages(array $pages, string $wikiCode): void
    {
        $this->info(sprintf('Process wiki %s - %s Pages', $wikiCode, $count = count($pages)));
        foreach ($pages as $page) {

            $pageModel = PageModel::query()
                ->where('page_id', $page['pageid'])
                ->where('wiki', $wikiCode)
                ->first();

            if (!isset($pageModel)) {
                $pageModel = new PageModel();
                $pageModel->dry = true;
            }

            $pageModel->page_id = $page['pageid'];
            $pageModel->title = $page['title'];
            $pageModel->wiki = $wikiCode;
            $pageModel->save();
        }

        $this->info(sprintf('End process %s Pages', $count));
    }

    private function handleImport(mixed $localeConfig): void
    {
        $client = new WikiClient($localeConfig->toArray());
        $wikiCode = $localeConfig->code;
        $this->info(sprintf("Importing Pages from wiki %s", $wikiCode));


        // Repeat for Main, Categories and Structures
        foreach ([0, 14, 3000] as $namespace) {
            $this->info("Importing Pages from namespace $namespace");

            $content = $client->searchPages($namespace);
            $pages = $content['query']['allpages'];

            $this->handlePages($pages, $wikiCode);

            $continue = $content['continue']['apcontinue'] ?? null;

            while ($continue !== null && $continue !== '') {

                $opts = ['apcontinue' => $continue];
                $content = $client->searchPages($namespace, $opts);
                $pages = $content['query']['allpages'];

                $this->handlePages($pages, $wikiCode);

                $continue = $content['continue']['apcontinue'] ?? null;
            }
        }
    }
}
