<?php


namespace App\Console\Commands;

use App\Src\UseCases\Infra\Sql\Model\PageModel;
use GuzzleHttp\Client;
use Illuminate\Console\Command;

class SyncDryPagesFromWiki extends Command
{
    protected $signature = 'pages:sync-dry';

    protected $description = 'Sync the pages from the wiki';

    private $queryPages = '?action=query&redirects=true&prop=info&format=json&pageids=';
    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $httpClient = new Client();

        PageModel::query()->where('dry', true)->chunkById(50, function ($items, $count) use($httpClient){
            $this->info(($count*50).' Pages');
            $pages = $items->pluck('page_id')->toArray();
            $pagesApiUri = config('wiki.api_uri').$this->queryPages.implode('|', $pages);
            $response = $httpClient->get($pagesApiUri);
            $content = json_decode($response->getBody()->getContents(), true);
            $wikiPages = $content['query']['pages'];

            foreach($wikiPages as $page){
                $pageModel = PageModel::query()->where('page_id', $page['pageid'])->first();
                $pageModel->dry = false;
                $pageModel->title = $page['title'];
                $pageModel->last_sync = (new \DateTime());
                $pageModel->save();
            }
        });


    }
}
