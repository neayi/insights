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

    public function handle(): void
    {
        $localesConfig = LocalesConfig::all();

        foreach ($localesConfig as $localeConfig) {
            if (!$localeConfig->forum_taggroup_themes) {
                $this->info(sprintf('Skipping wiki %s, no forum taggroup', $localeConfig->code));
                continue;
            }

            try {
                $this->handleSync($localeConfig);
            } catch (\Throwable $e) {
                $this->error(get_class($e));
                $this->error(sprintf('Error syncing wiki pages to forum %s: %s', $localeConfig->code, $e->getMessage()));
            }
        }
    }

    private function handleSync(mixed $localeConfig): void
    {
        $wikiCode = $localeConfig->code;
        $this->info(sprintf("Syncing wiki Pages to forum %s", $wikiCode));

        // TODO: Set higher limit for GROUP_CONCAT (default is 1024 characters)
        // SET SESSION group_concat_max_len = 1000000;

        // Get eligible pages (followed by at least one user)
        // Eloquent seems not to be optimized to user INNER JOIN in order to filter, using SQL
        $eligiblePagesQuery = DB::table('pages', 'p')
            ->select('p.page_id', 'p.title', DB::raw('GROUP_CONCAT(users.discourse_username) AS usernames'))
            ->join('interactions', 'interactions.page_id', '=', 'p.page_id')
            ->join('users', 'interactions.user_id', '=', 'users.id')
            ->where([
                ['p.wiki', '=', '?'],
                ['p.is_tag', '=', '?'],
                ['interactions.follow', '=', '?']
            ])
            ->whereNotNull('interactions.user_id')
            ->groupBy('p.page_id', 'p.title')
            ->setBindings([$wikiCode, true, true])
        ;

        $pages = $eligiblePagesQuery->get();

        dump($pages->all());


        foreach ($pages as $page) {
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
