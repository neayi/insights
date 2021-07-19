<?php


namespace App\Console\Commands;

use App\Src\UseCases\Infra\Sql\Model\PageModel;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class ImportPagesWithIconAndTypeFromWiki extends Command
{
    protected $signature = 'pages:import-with-icons-type';

    protected $description = 'Import pages with icons and types from the wiki';

    protected $httpClient;
    protected $queryPicture = '?action=query&redirects=true&format=json&prop=imageinfo&iiprop=url&titles=';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $this->httpClient = new Client();
        Storage::makeDirectory('public/pages');
        $queryPages = "?action=ask&format=json&api_version=3&query=[[A un type de page::%2B]]|?A un fichier d'icone de caractéristique|?A un type de page";

        $pagesApiUri = config('wiki.api_uri').$queryPages;

        $response = $this->httpClient->get($pagesApiUri);
        $content = json_decode($response->getBody()->getContents(), true);

        $pages = $content['query']['results'];

        $this->handlePages($pages);
        $continue = $content['query-continue-offset'] ?? null;

        while($continue !== null && $continue !== ''){
            $this->info($continue);

            $pagesApiUri = config('wiki.api_uri').$queryPages.'|offset='.$continue;
            $response = $this->httpClient->get($pagesApiUri);
            $content = json_decode($response->getBody()->getContents(), true);
            $pages = $content['query']['results'];
            $this->handlePages($pages);
            $continue = $content['query-continue-offset'] ?? null;
        }
    }

    private function handlePages($pages)
    {
        foreach ($pages as $page) {
            $title = key($page);
            $page = last($page);
            $typePage = last($page['printouts']['A un type de page']);
            $icon = last($page['printouts']['A un fichier d\'icone de caractéristique']);

            $pageModel = PageModel::where('title', $title)->first();
            if (!isset($pageModel)) {
                $this->info('Page not found :  '.$title);
                continue;
            }

            if($icon !== false) {
                $picturesApiUri = config('wiki.api_uri').$this->queryPicture.$icon['fulltext'];

                $response = $this->httpClient->get($picturesApiUri);
                $content = json_decode($response->getBody()->getContents(), true);
                $picturesInfo = $content['query']['pages'];
                foreach($picturesInfo as $picture) {
                    if (isset(last($picture['imageinfo'])['url'])) {
                        try {
                            $response = $this->httpClient->get(last($picture['imageinfo'])['url']);
                            $content = $response->getBody()->getContents();
                            $path = 'public/pages/' . $pageModel->id . '.png';
                            Storage::put('public/pages/' . $pageModel->id . '.png', $content);
                        }catch (ClientException $e){
                            $path = '';
                        }
                    }else{
                        $path = '';
                    }
                }
                $pageModel->icon = $path;
            }

            $pageModel->type = $typePage !== false ? $typePage : '';
            $pageModel->save();
        }
    }
}
