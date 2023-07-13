<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Src\UseCases\Infra\Sql\Model\PageModel;
use GuzzleHttp\Client;
use Illuminate\Console\Command;

class SyncDryPagesFromWiki extends Command
{
    protected $signature = 'pages:sync-dry {wiki}';

    protected $description = 'Sync the pages from the wiki';

    private string $queryPages = '?action=query&redirects=true&prop=info&format=json&prop=pageimages&pithumbsize=250&pageids=';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $httpClient = new Client();
        $wikiCode = $this->argument('wiki');
        $baseUri = config(sprintf('wiki.api_uri_%s', $wikiCode));

        PageModel::query()->where('dry', true)->chunkById(50, function ($items, $count) use($httpClient, $baseUri){
            $this->info(($count*50).' Pages');
            $pages = $items->pluck('page_id')->toArray();
            $pagesApiUri = $baseUri.$this->queryPages.implode('|', $pages);
            $response = $httpClient->get($pagesApiUri);
            $content = json_decode($response->getBody()->getContents(), true);
            $wikiPages = $content['query']['pages'];

            foreach($wikiPages as $page){
                $pageModel = PageModel::query()->where('page_id', $page['pageid'])->first();

                if(!isset($pageModel)){
                    continue;
                }

                if (!isset($page['title'])) {
                    // The page has been deleted from the wiki, we remove it on our side too
                    $pageModel->delete();
                    continue;
                }

                $pageModel->dry = false;
                $pageModel->title = $page['title'];
                $pageModel->last_sync = (new \DateTime());
                $pageModel->picture = $page['thumbnail']['source'] ?? null;
                $pageModel->save();
            }
        });
    }
}
