<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Src\UseCases\Infra\Sql\Model\PageModel;
use App\Src\WikiClient;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Console\Command;

class ImportAllPagesFromWiki extends Command
{
    protected $signature = 'pages:import-all {country_code}';

    protected $description = 'Import all pages from the wiki';

    /**
     * @throws GuzzleException
     */
    public function handle(): void
    {
        $countryCode = $this->argument('country_code');
        $client = new WikiClient($countryCode);

        // Repeat for Main, Categories and Structures
        foreach ([0, 14, 3000] as $namespace)
        {
            $this->info("Importing Pages from namespace $namespace");

            $content = $client->searchPages($namespace);
            $pages = $content['query']['allpages'];

            $this->handlePages($pages, $countryCode);

            $continue = $content['continue']['apcontinue'] ?? null;

            while($continue !== null && $continue !== ''){

                $opts = ['apcontinue' => $continue];
                $content = $client->searchPages($namespace, $opts);
                $pages = $content['query']['allpages'];

                $this->handlePages($pages, $countryCode);

                $continue = $content['continue']['apcontinue'] ?? null;
            }
        }
    }

    private function handlePages(array $pages, string $countryCode): void
    {
        $this->info(sprintf('Process %s Pages', $count = count($pages)));
        foreach ($pages as $page) {

            $pageModel = PageModel::query()
                ->where('page_id', $page['pageid'])
                ->where('country_code', $countryCode)
                ->first();

            if (!isset($pageModel)) {
                $pageModel = new PageModel();
            }

            $pageModel->page_id = $page['pageid'];
            $pageModel->dry = true;
            $pageModel->title = $page['title'];
            $pageModel->picture = $page['thumbnail']['source'] ?? null;
            $pageModel->country_code = $countryCode;
            $pageModel->save();
        }

        $this->info(sprintf('End process %s Pages', $count));
    }
}
