<?php


namespace App\Console\Commands;

use App\Src\UseCases\Domain\Ports\PageRepository;
use App\Src\UseCases\Infra\Sql\Model\PageModel;
use GuzzleHttp\Client;
use Illuminate\Console\Command;

class ImportPagesWithIconAndTypeFromWiki extends Command
{
    protected $signature = 'pages:import-with-icons-type';

    protected $description = 'Import pages with icons and types from the wiki';


    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $httpClient = new Client();
        $queryPages = "?action=ask&api_version=3&query=[[A un type de page::+]]|?A un fichier d'icone de caractéristique|?A un type de page&format=jsonfm";

        $pagesApiUri = config('wiki.api_uri').$queryPages;

        $response = $httpClient->get($pagesApiUri);
        $content = json_decode($response->getBody()->getContents(), true);

        $pages = $content['query']['pages'];

        $this->handlePages($pages);
        $continue = $content['continue']['gapcontinue'] ?? null;

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
        foreach ($pages as $title => $page) {
            $pageModel = PageModel::where('title', $title)->first();
            if (!isset($pageModel)) {
                continue;
            }
            $pageModel->page_id = $page['pageid'];
            $pageModel->dry = true;
            $pageModel->icon = $page['title'];
            $pageModel->type = $page['thumbnail']['source'] ?? null;
            $pageModel->save();
        }
    }
}
