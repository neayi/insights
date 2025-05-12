<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\LocalesConfig;
use App\Src\UseCases\Infra\Sql\Model\PageModel;
use App\Src\WikiClient;
use DB;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Console\Command;

class SyncPagesToForum extends Command
{
    protected $signature = 'pages:sync-to-forum';

    protected $description = 'Create forum tags for eligible wiki pages';

    /**
     * @var array<string, int[]>
     */
    protected array $followedPagesIds = [];

    public function handle(): void
    {
        $localesConfig = LocalesConfig::all();

        foreach ($localesConfig as $localeConfig) {
            try {
                $this->setFollowedPages($localeConfig);
                $this->handleSync($localeConfig);
            } catch (\Throwable $e) {
                $this->error(get_class($e));
                $this->error(sprintf('Error syncing wiki pages to forum %s: %s', $localeConfig->code, $e->getMessage()));
            }
        }
    }

    private function setFollowedPages(LocalesConfig $localeConfig): void
    {
        $localeCode = $localeConfig->toArray()['code'];

        // Get eligible pages (followed by at least one user)
        // Eloquent seems not to be optimized to user INNER JOIN in order to filter, using SQL
        $eligiblePagesQuery = DB::table('pages', 'p')
            ->select('p.page_id')
            ->join('interactions', 'interactions.page_id', '=', 'p.page_id')
            ->where([
                ['p.wiki', '=', ':localeCode'],
                ['interactions.follow', '=', ':true']
            ])
            ->whereNotNull('interactions.user_id')
            ->groupBy('p.page_id')
            ->setBindings(['localeCode' => $localeCode, 'true' => 1])
        ;

        $pages = $eligiblePagesQuery->get();

        $pagesIds = array_map(fn ($page) => (int)$page->page_id, $pages->all());

        $this->followedPagesIds[$localeCode] = $pagesIds;
    }

    private function handleSync(mixed $localeConfig): void
    {
        $client = new WikiClient($localeConfig->toArray());
        $wikiCode = $localeConfig->code;
        $this->info(sprintf("Syncing wiki Pages to forum %s", $wikiCode));

        // API query to get existing pages having at least 1 keyword
        // https://wiki.dev.tripleperformance.fr/api.php?action=ask&api_version=3&query=[[-A%20un%20mot-cl%C3%A9::%2B]][[Page%20ID::%2B]]|?Page%20ID|limit=5000&format=json

        $query = '[[-A un mot-clé::+]][[Page ID::+]]|?Page ID|limit=10000';

        $content = $client->ask($query);

        dd($content['query']['results'][0]);
    }
}
