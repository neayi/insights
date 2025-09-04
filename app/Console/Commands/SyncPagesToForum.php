<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\LocalesConfig;
use App\Src\ForumApiClient;
use App\Src\UseCases\Domain\Forum\ForumTagHelper;
use Illuminate\Support\Facades\DB;
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
            ->where('p.wiki', '=', $wikiCode)
            ->where('p.is_tag', '=', 1)
            ->where('interactions.follow', '=', 1)
            ->where(DB::raw('CHAR_LENGTH(p.title)'), '<', 25)
            ->whereNotNull('interactions.user_id')
            ->groupBy('p.page_id', 'p.title', 'p.wiki_ns')
        ;

        $pages = $eligiblePagesQuery->get();

        $tagsSubscriptionsForUsers = [];
        $tags = [];

        foreach ($pages as $page) {
            // Do not process pages without users subscribed
            if (empty($page->usernames)) {
                $this->info(sprintf('Skipping page "%s" with namespace %d, no users subscribed', $page->title, $page->wiki_ns));
                continue;
            }

            $pageName = $page->title;
            $pageNs = $page->wiki_ns;

            try {
                $cleanedPageName = $this->removeNamespace($pageName, $pageNs);
                $sanitizedPageName = ForumTagHelper::sanitizeTagName($cleanedPageName);
                $tags[$sanitizedPageName] = $sanitizedPageName;

                foreach (explode(',', $page->usernames) as $username) {
                    $tagsSubscriptionsForUsers[$username][] = $sanitizedPageName;
                }
            } catch (Throwable $e) {
                $this->error(sprintf('Error handling page "%s" with namespace %d : %s', $pageName, $pageNs, $e->getMessage()));
                continue;
            }
        }

        // Update tag group in forum
        $forumClient->updateTagGroup($localeConfig->forum_taggroup_themes, $tags);

        $this->info(sprintf('Updated tag group for wiki %s with %d tags', $wikiCode, count($tags)));

        // Inscrire les utilisateurs aux notifs du tag
        $subscriptionsDone = 0;
        foreach ($tagsSubscriptionsForUsers as $username => $tags) {
            try {
                $currentlyFollowedTags = $forumClient->getFollowedTagsForUser($username);
            } catch (Throwable $e) {
                $this->error(sprintf('Error fetching followed tags for user %s: %s', $username, $e->getMessage()));
                continue;
            }

            foreach ($tags as $tagName) {
                if (in_array($tagName, $currentlyFollowedTags, true)) {
                    $this->info(sprintf('User %s is already subscribed to tag %s, skipping', $username, $tagName));
                    continue;
                }

                try {
                    $forumClient->subscribeTagNotifications($username, $tagName);
                    $subscriptionsDone++;
                    $this->info(sprintf('Subscribed user %s to tag %s', $username, $tagName));
                } catch (Throwable $e) {
                    $this->error(sprintf('Error subscribing user %s to tag %s: %s', $username, $tagName, $e->getMessage()));
                }
            }
        }

        $this->info(sprintf('Made %d subscriptions', $subscriptionsDone));
    }

    /**
     * Remove the namespace from the page name based on the namespace ID.
     */
    private function removeNamespace(string $pageName, int $pageNs): string
    {
        switch ($pageNs) {
            case 0: // No namespace
                return $pageName;

            default:
                $indexOfColon = strpos($pageName, ':');
                return $indexOfColon !== false ? mb_substr($pageName, $indexOfColon) : $pageName;
        }
    }
}
