<?php


namespace App\Console\Commands;

use App\Src\UseCases\Domain\Ports\PageRepository;
use App\Src\UseCases\Infra\Sql\Model\PageModel;
use GuzzleHttp\Client;
use Illuminate\Console\Command;

class ImportAllPagesFromWiki extends Command
{
    protected $signature = 'pages:import-all';

    protected $description = 'Import all pages from the wiki';


    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $httpClient = new Client();
        $queryPages = '?action=query&generator=allpages&gaplimit=500&gapfilterredir=nonredirects&format=json';

        $pagesApiUri = config('wiki.api_uri').$queryPages;
        $response = $httpClient->get($pagesApiUri);
        $content = json_decode($response->getBody()->getContents(), true);

        $pages = $content['query']['pages'];

        $this->handlePages($pages);
        $continue = $content['continue']['gapcontinue'];

        while($continue !== null && $continue !== ''){
            $this->info($continue);

            $pagesApiUri = config('wiki.api_uri').$queryPages.'&gapcontinue='.$continue;
            $response = $httpClient->get($pagesApiUri);
            $content = json_decode($response->getBody()->getContents(), true);
            $pages = $content['query']['pages'];
            $this->handlePages($pages);

            $continue = $content['continue']['gapcontinue'] ?? null;
        }
    }

    private function handlePages($pages)
    {
        foreach ($pages as $page) {
            $pageModel = PageModel::where('page_id', $page['pageid'])->first();
            if (!isset($pageModel)) {
                $pageModel = new PageModel();
            }
            $pageModel->page_id = $page['pageid'];
            $pageModel->dry = true;
            $pageModel->title = $page['title'];
            $pageModel->save();
        }
    }
}
