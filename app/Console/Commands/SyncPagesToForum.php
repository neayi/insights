<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\LocalesConfig;
use App\Src\WikiClient;
use DB;
use Illuminate\Console\Command;
use RuntimeException;
use Throwable;

class SyncPagesToForum extends Command
{
    protected $signature = 'pages:sync-to-forum';

    protected $description = 'Create forum tags for eligible wiki pages';

    private const PAGE_ID_KEY = [
        'fr' => 'Identifiant de page',
        'en' => 'Page ID',
        'de' => 'Seitenkennung',
    ];

    /**
     * @var array<string, int[]>
     */
    protected array $followedPagesIds = [];

    public function handle(): void
    {
        $localesConfig = LocalesConfig::all();

        foreach ($localesConfig as $localeConfig) {
            if (!$localeConfig->forum_taggroup_themes) {
                $this->info(sprintf('Skipping wiki %s, no forum taggroup', $localeConfig->code));
                continue;
            }

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

        foreach ($content['query']['results'] as $result) {

            $pageResult = array_values($result)[0];

            $wikiPageId = $pageResult['printouts'][self::PAGE_ID_KEY[$wikiCode]][0] ?? null;

            // Si la page n'est pas suivie par au moins un utilisateur, on ne la traite pas
            if (
                null === $wikiPageId
                || !in_array($wikiPageId, $this->followedPagesIds[$wikiCode])
            ) {
                continue;
            }

            $pageName = $pageResult['fulltext'];
            $pageNs = $pageResult['namespace'];

            try {
                $pageName = $this->handlePageNamespace($wikiCode, $pageName, $pageNs);

                // TODO: create forum tag
                // TODO: inscrire utilisateurs aux notifs du tag
            } catch (Throwable $e) {
                $this->error(sprintf('Error handling page "%s" with namespace %d : %s', $pageName, $pageNs, $e->getMessage()));
                continue;
            }

            dump($pageName);
        }

    }

    private function handlePageNamespace(string $wikiCode, string $pageName, int $pageNs): string
    {
        switch ($pageNs) {
            // Main
            case 0:
                return $pageName;
            // Catégorie/Category/Kategorie
            case 14:
                switch ($wikiCode) {
                    case 'fr':
                        $prefixLength = 10;
                        break;
                    case 'en':
                        $prefixLength = 9;
                        break;
                    case 'de':
                        $prefixLength = 10;
                        break;
                    default:
                        throw new RuntimeException(
                            sprintf(
                                'Unhandled wiki code %s for namespace %d and page name "%s"',
                                $wikiCode, $pageNs, $pageName
                            )
                        );
                        break;
                }
                return mb_substr($pageName, $prefixLength);
            default:
                throw new RuntimeException(sprintf('Unhandled namespace %d for page name "%s"', $pageNs, $pageName));
        }
    }
}
