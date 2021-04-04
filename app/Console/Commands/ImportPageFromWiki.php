<?php


namespace App\Console\Commands;

use App\Src\UseCases\Domain\Ports\PageRepository;
use App\Src\UseCases\Infra\Sql\Model\PageModel;
use GuzzleHttp\Client;
use Illuminate\Console\Command;

class ImportPageFromWiki extends Command
{
    protected $signature = 'pages:import';

    protected $description = 'Import the pages from the wiki';



    public function __construct()
    {
        parent::__construct();
    }

    public function handle(PageRepository $pageRepository)
    {
        $httpClient = new Client();
        $queryPages = '?action=query&redirects=true&prop=info&format=json&titles=';

        $pages = [];
        PageModel::query()->where('dry', true)->chunk(100, function ($item) use($pages){
            $pages[] = $item->title;
        });

        $pagesApiUri = config('wiki.api_uri').$queryPages.implode('|', $pages);
        $response = $httpClient->get($pagesApiUri);
        $content = json_decode($response->getBody()->getContents(), true);


    }
}
