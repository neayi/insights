<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\LocalesConfig;
use App\Src\ForumApiClient;
use App\Src\UseCases\Domain\Forum\ForumTagHelper;
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

    private function handleSync(LocalesConfig $localeConfig): void
    {
        $wikiCode = $localeConfig->code;
        $forumClient = new ForumApiClient($localeConfig->forum_api_url, $localeConfig->forum_api_key);

        $this->info(sprintf("Syncing wiki Pages to forum %s", $wikiCode));

        // Set higher limit for GROUP_CONCAT (default is 1024 characters)
        // 4GB max length is the maximum value for 32-bit systems
        // Maximum value for 64-bit systems is 18446744073709551615
        DB::statement('SET SESSION group_concat_max_len = 4294967295');

        // Get eligible pages (followed by at least one user)
        // Eloquent seems not to be optimized to user INNER JOIN in order to filter, using SQL
        $eligiblePagesQuery = DB::table('pages', 'p')
            ->select('p.page_id', 'p.title', 'p.wiki_ns', DB::raw('GROUP_CONCAT(users.discourse_username) AS usernames'))
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

        $tagsSubscriptions = [];
        foreach ($pages as $page) {
            $pageName = $page['title'];
            $pageNs = $page['wiki_ns'];

            dd($pageName, $pageNs);

            try {
                $cleanedPageName = $this->handlePageNamespace($wikiCode, $pageName, $pageNs);
                $sanitizedPageName = ForumTagHelper::sanitizeTagName($cleanedPageName);
                $tagsSubscriptions[$sanitizedPageName] = explode(',', $page['usernames']);
            } catch (Throwable $e) {
                $this->error(sprintf('Error handling page "%s" with namespace %d : %s', $pageName, $pageNs, $e->getMessage()));
                continue;
            }

            dd($tagsSubscriptions);

            // Update tag group in forum
            $forumClient->updateTagGroup(
                $localeConfig->forum_taggroup_themes,
                array_keys($tagsSubscriptions)
            );
            $this->info(sprintf('Updated tag group for wiki %s with %d tags', $wikiCode, count($tagsSubscriptions)));

            // Inscrire les utilisateurs aux notifs du tag
            foreach ($tagsSubscriptions as $tagName => $usernames) {
                foreach ($usernames as $username) {
                    try {
                        $forumClient->subscribeTagNotifications($username, $tagName);
                        $this->info(sprintf('Subscribed user %s to tag %s', $username, $tagName));
                    } catch (Throwable $e) {
                        $this->error(sprintf('Error subscribing user %s to tag %s: %s', $username, $tagName, $e->getMessage()));
                    }
                }
            }
            $this->info(sprintf('Subscribed %d users to tag %s', count($tagsSubscriptions[$sanitizedPageName]), $sanitizedPageName));
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
