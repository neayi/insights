<?php

declare(strict_types=1);

namespace App\Src\UseCases\Domain\Forum;

use App\LocalesConfig;
use App\Src\ForumApiClient;
use App\Src\UseCases\Domain\Context\Model\Characteristic;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class CharacteristicsForumSyncer
{
    /** array<string, {client: ForumApiClient}> */
    private array $syncerConfig = [];

    /**
     * @inheritdoc
     */
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

    public function subscribeCharacteristicTagNotifications(string $username, string $locale, string $tagName): void
    {
        if (empty($this->syncerConfig)) {
            $this->initSyncerConfig();
        }

        Log::info(sprintf('Subscribing user %s to tag %s in wiki %s', $username, $tagName, $locale));

        $forumApiClient = $this->syncerConfig[$locale]['client'];

        try {
            $forumApiClient->subscribeTagNotifications($username, ForumTagHelper::sanitizeTagName($tagName));
        } catch (\Throwable $e) {
            Log::error(sprintf('Error subscribing user %s to tag %s: %s', $username, $tagName, $e->getMessage()));
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
