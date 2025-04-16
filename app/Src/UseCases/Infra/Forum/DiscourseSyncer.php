<?php

declare(strict_types=1);

namespace App\Src\UseCases\Infra\Forum;

use App\LocalesConfig;
use App\Src\ForumApiClient;
use App\Src\UseCases\Domain\Context\Model\Characteristic;
use App\Src\UseCases\Domain\Forum\CharacteristicsForumSyncer;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class DiscourseSyncer implements CharacteristicsForumSyncer
{
    /** array<string, {client: ForumApiClient}> */
    private array $syncerConfig = [];

    public function __construct()
    {
        foreach (LocalesConfig::all() as $wikiLocale) {
            $this->syncerConfig[$wikiLocale->code]['client'] = new ForumApiClient($wikiLocale->forum_api_url, $wikiLocale->forum_api_key);

            $this->syncerConfig[$wikiLocale->code]['characteristics_taggroups'] = [
                Characteristic::FARMING_TYPE => $wikiLocale->forum_taggroup_farming,
                Characteristic::CROPPING_SYSTEM => $wikiLocale->forum_taggroup_cropping,
            ];
        }
    }

    /**
     * @inheritdoc
     */
    public function syncCharacteristicTagGroup(string $type, string $locale, array $characteristics): void
    {
        Log::info(sprintf('Syncing characteristic tag group for type %s and locale %s to the forum', $type, $locale));

        $tagGroupId = $this->getTagGroupId($type, $locale);
        if (null === $tagGroupId) {
            Log::notice(sprintf('No tag group found for type %s and locale %s', $type, $locale));

            return;
        }

        $tagNames = array_map(fn ($characteristic) => $this->sanitizeTagName($characteristic->label()), $characteristics);

        dump($tagGroupId, $tagNames);return;

        $this->syncerConfig[$locale]['client']->updateTagGroup($tagGroupId, $tagNames);
    }

    /**
     * Based on what we deduced from the Discourse tag names treatment :
     * - squish extra spaces
     * - keeps uppercase and accentuation
     * - replaces spaces with dashes
     */
    private function sanitizeTagName(string $tagName): string
    {
        $squishedName = Str::squish($tagName);
        $dashedName = str_replace(' ', '-', $squishedName);

        // Merge consecutive dashes
        return preg_replace('/-+/', '-', $dashedName);
    }

    private function getTagGroupId(string $type, string $localeCode): ?int
    {
        return $this->syncerConfig[$localeCode]['characteristics_taggroups'][$type] ?? null;
    }
}