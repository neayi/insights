<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\LocalesConfig;
use App\Src\UseCases\Infra\Sql\Model\PageModel;
use App\Src\WikiClient;
use GuzzleHttp\Client;
use Illuminate\Console\Command;

class SyncDryPagesFromWiki extends Command
{
    protected $signature = 'pages:sync-dry';

    protected $description = 'Sync the pages from the wiki';

    public function handle()
    {
        $localesConfig = LocalesConfig::all();

        foreach ($localesConfig as $localeConfig) {
            $client = new WikiClient($localeConfig->toArray());
            $wikiCode = $localeConfig->code;
            $wikiUrl = $localeConfig->wiki_url;

            PageModel::query()
                ->where('dry', true)
                ->where('wiki', $wikiCode)
                ->chunkById(50, function ($items, $count) use($client, $wikiUrl){
                    $this->info(($count*50).' Pages');
                    $pages = $items->pluck('page_id')->toArray();
                    $content = $client->searchPagesById($pages);
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
                        if ($page['pageimage'] ?? false) {
                            $pageModel->picture = sprintf('%s/wiki/Special:FilePath/File:%s', $wikiUrl, urlencode($page['pageimage']));
                        } else {
                            $pageModel->picture = null;
                        }
                        $pageModel->save();
                    }
                });
        }


    }
}
