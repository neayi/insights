<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Src\UseCases\Infra\Sql\Model\PageModel;
use GuzzleHttp\Client;
use Illuminate\Console\Command;

class ImportAllPagesFromWiki extends Command
{
    protected $signature = 'pages:import-all {country_code}';

    protected $description = 'Import all pages from the wiki';


    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $countryCode = $this->argument('country_code');
        $baseUri = config(sprintf('wiki.api_uri_%s', $countryCode));

        // Repeat for Main, Categories and Structures
        foreach ([0, 14, 3000] as $namespace)
        {
            $httpClient = new Client();
            $queryPages = '?action=query&list=allpages&apnamespace=' . $namespace . '&aplimit=500&apfilterredir=nonredirects&format=json';

            $pagesApiUri = $baseUri.$queryPages;

            $response = $httpClient->get($pagesApiUri);
            $content = json_decode($response->getBody()->getContents(), true);

            $pages = $content['query']['allpages'];

            $this->handlePages($pages, $countryCode);
            $continue = $content['continue']['apcontinue'] ?? null;

            while($continue !== null && $continue !== ''){
                $this->info($continue);

                $pagesApiUri = $baseUri.$queryPages.'&apcontinue='.$continue;

                $response = $httpClient->get($pagesApiUri);
                $content = json_decode($response->getBody()->getContents(), true);
                $pages = $content['query']['allpages'];

                $this->handlePages($pages, $countryCode);

                $continue = $content['continue']['apcontinue'] ?? null;
            }
        }
    }

    private function handlePages($pages, string $countryCode)
    {
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
    }
}
