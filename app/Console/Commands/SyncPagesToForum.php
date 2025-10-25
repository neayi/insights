<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\LocalesConfig;
use App\Src\ForumApiClient;
use App\Src\UseCases\Domain\Forum\ForumTagHelper;
use App\Src\UseCases\Domain\Forum\ForumUserProvisioner;
use Illuminate\Support\Facades\DB;
use Illuminate\Console\Command;
use Throwable;

class SyncPagesToForum extends Command
{
    protected $signature = 'pages:sync-to-forum';

    protected $description = 'Create forum tags for eligible wiki pages';

    private ForumUserProvisioner $forumUserProvisioner;

    private int $doneSubscriptions = 0;

    public function handle(ForumUserProvisioner $forumUserProvisioner): void
    {
        $this->forumUserProvisioner = $forumUserProvisioner;

        $localesConfig = LocalesConfig::all();

        $this->doneSubscriptions = 0;

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

        $usersDiscourseIds = $this->createUsersOnDiscourse($localeConfig);

        $forumClient = new ForumApiClient($localeConfig->forum_api_url, $localeConfig->forum_api_key);

        $this->info(sprintf("Syncing wiki Pages to forum %s", $wikiCode));

        // Set higher limit for GROUP_CONCAT (default is 1024 characters)
        // 4GB max length is the maximum value for 32-bit systems
        // Maximum value for 64-bit systems is 18446744073709551615
        DB::statement('SET SESSION group_concat_max_len = 4294967295');

        // Get eligible pages (followed by at least one user)
        // Eloquent seems not to be optimized to user INNER JOIN in order to filter, using SQL
        $eligiblePagesQuery = DB::table('pages', 'p')
            ->select('p.page_id', 'p.title', 'p.wiki_ns', DB::raw('GROUP_CONCAT(DISTINCT users.id) AS user_ids'))
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
            if (empty($page->user_ids)) {
                $this->info(sprintf('Skipping page "%s" with namespace %d, no users subscribed', $page->title, $page->wiki_ns));
                continue;
            }

            $pageName = $page->title;
            $pageNs = $page->wiki_ns;

            try {
                $cleanedPageName = $this->removeNamespace($pageName, $pageNs);
                $sanitizedPageName = ForumTagHelper::sanitizeTagName($cleanedPageName);
                $tags[$sanitizedPageName] = $sanitizedPageName;

                foreach (explode(',', $page->user_ids) as $userId) {
                    $tagsSubscriptionsForUsers[$userId][] = $sanitizedPageName;
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
        foreach ($tagsSubscriptionsForUsers as $userId => $tags) {
            $this->subscribeUserToTags($forumClient, $localeConfig->code, (int) $userId, $usersDiscourseIds[$userId] ?? null, $tags);
        }

        $this->info(sprintf('Made %d subscriptions', $this->doneSubscriptions));
    }

    private function subscribeUserToTags(ForumApiClient $forumClient, string $locale, int $userId, ?string $discourseUsername, array $tags): void
    {
        // De‑dup incoming tags for this user
        $tags = array_values(array_unique($tags));

        if (null === $discourseUsername) {
            $this->info(sprintf('No discourse username found for user ID %d and locale %s, skipping', $userId, $locale));

            return;
        }

        try {
            $currentlyFollowedTags = $forumClient->getFollowedTagsForUser($discourseUsername);
        } catch (Throwable $e) {
            $this->error(sprintf('Error fetching followed tags for user %s: %s', $discourseUsername, $e->getMessage()));

            return;
        }

        foreach ($tags as $tagName) {
            if (in_array($tagName, $currentlyFollowedTags, true)) {
                $this->info(sprintf('User %s is already subscribed to tag %s, skipping', $discourseUsername, $tagName));
                continue;
            }

            try {
                $forumClient->subscribeTagNotifications($discourseUsername, $tagName);
                $this->doneSubscriptions++;
                $this->info(sprintf('Subscribed user %s to tag %s', $discourseUsername, $tagName));
            } catch (Throwable $e) {
                $this->error(sprintf('Error subscribing user %s to tag %s: %s', $discourseUsername, $tagName, $e->getMessage()));
            }
        }
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

    /**
     * Find all the users that follow at least one page, and make sure they have a forum account (so that they can be contacted by private message or that the can get notifications).
     */
    private function createUsersOnDiscourse(LocalesConfig $localeConfig): array
    {
        $wikiCode = $localeConfig->code;

        $this->info(sprintf("Syncing followers to forum %s", $wikiCode));

        // Get eligible pages (followed by at least one user)
        // Eloquent seems not to be optimized to user INNER JOIN in order to filter, using SQL
        $users = DB::table('users')
            ->join('interactions', function($join) use ($wikiCode) {
                $join->on('interactions.user_id', '=', 'users.id')
                    ->where('interactions.follow', '=', 1)
                    ->where('interactions.wiki', '=', $wikiCode);
            })
            ->whereNotNull('users.email_verified_at')
            ->distinct()
            ->select('users.*')->get();

        $usersDiscourseIds = [];
        foreach ($users as $user) {
            $discourseUsername = $this->forumUserProvisioner->getUserDiscourseUsername($user->id, $localeConfig->code);

            if ($discourseUsername !== null) {
                $usersDiscourseIds[$user->id] = $discourseUsername;
            }
            else {
                $this->info(sprintf('User NOT synced on discourse: ID %d (%s)', $user->id, $user->email));
            }
        }

        $this->info(sprintf("Synched %d followers (people which follow at least one page and have verified their email)", count($usersDiscourseIds)));

        return $usersDiscourseIds;
    }
}
