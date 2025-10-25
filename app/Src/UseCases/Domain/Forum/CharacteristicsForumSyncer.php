<?php

declare(strict_types=1);

namespace App\Src\UseCases\Domain\Forum;

use App\LocalesConfig;
use App\Src\ForumApiClient;
use App\Src\UseCases\Domain\Context\Model\Characteristic;
use Illuminate\Support\Facades\Log;

class CharacteristicsForumSyncer
{
    private ForumUserProvisioner $forumUserProvisioner;

    /**
     * @var array<string, {client: ForumApiClient, characteristics_taggroups: array<string, int>}>
     */
    private array $syncerConfig = [];

    public function __construct(ForumUserProvisioner $forumUserProvisioner)
    {
        $this->forumUserProvisioner = $forumUserProvisioner;
    }

    public function syncCharacteristicTagGroup(string $type, string $locale, array $characteristics): void
    {
        if (empty($this->syncerConfig)) {
            $this->initSyncerConfig();
        }

        Log::info(sprintf('Syncing characteristic tag group for type %s and locale %s to the forum', $type, $locale));

        $tagGroupId = $this->getTagGroupId($type, $locale);
        if (null === $tagGroupId) {
            Log::notice(sprintf('No tag group found for type %s and locale %s', $type, $locale));

            return;
        }

        $newTagNames = array_map(
            fn ($characteristic) => ForumTagHelper::sanitizeTagName($characteristic->label() ?? $characteristic->title()),
            $characteristics
        );
        $existingTagNames = $this->syncerConfig[$locale]['client']->getTagGroup($tagGroupId)['tag_group']['tag_names'] ?? [];

        // We keep existing tags in place
        $tagNamesToSync = array_unique(array_merge($newTagNames, $existingTagNames));

        try {
            $this->syncerConfig[$locale]['client']->updateTagGroup($tagGroupId, $tagNamesToSync);
        } catch (\Throwable $e) {
            Log::error(sprintf('Error updating tag group %s for type %s and locale %s: %s', $tagGroupId, $type, $locale, $e->getMessage()));
        }
    }

    public function subscribeCharacteristicTagNotifications(int $userId, string $locale, string $tagName): void
    {
        if (empty($this->syncerConfig)) {
            $this->initSyncerConfig();
        }

        Log::info(sprintf('Subscribing user with ID %d to tag %s in wiki %s', $userId, $tagName, $locale));

        $discourseUsername = $this->forumUserProvisioner->getUserDiscourseUsername($userId, $locale);
        if (null === $discourseUsername) {
            Log::info(sprintf('No discourse username found for user ID %d and locale %s, skipping', $userId, $locale));

            return;
        }

        try {
            $this->syncerConfig[$locale]['client']->subscribeTagNotifications($discourseUsername, ForumTagHelper::sanitizeTagName($tagName));
        } catch (\Throwable $e) {
            Log::error(sprintf('Error subscribing user %s to tag %s: %s', $discourseUsername, $tagName, $e->getMessage()));
        }
    }

    private function getTagGroupId(string $type, string $localeCode): ?int
    {
        return $this->syncerConfig[$localeCode]['characteristics_taggroups'][$type] ?? null;
    }

    private function initSyncerConfig()
    {
        foreach (LocalesConfig::all() as $wikiLocale) {
            $this->syncerConfig[$wikiLocale->code]['client'] = new ForumApiClient($wikiLocale->forum_api_url, $wikiLocale->forum_api_key);

            $this->syncerConfig[$wikiLocale->code]['characteristics_taggroups'] = [
                Characteristic::FARMING_TYPE => $wikiLocale->forum_taggroup_farming,
                Characteristic::CROPPING_SYSTEM => $wikiLocale->forum_taggroup_cropping,
            ];
        }
    }
}
