<?php

declare(strict_types=1);

namespace App\Src\UseCases\Domain\Forum;

use Illuminate\Support\Str;

class ForumTagHelper
{
    /**
     * Based on what we deduced from the Discourse tag names treatment :
     * - squish extra spaces
     * - keeps uppercase and accentuation
     * - replaces spaces with dashes
     */
    public static function sanitizeTagName(string $tagName): string
    {
        $squishedName = Str::squish($tagName);
        $dashedName = str_replace(' ', '-', $squishedName);

        // \pL - matches any kind of letter from any language
        // \pM - matches a character intended to be combined with another character (e.g. accents, umlauts, enclosing boxes, etc.)
        $sanitizedName = mb_ereg_replace('[^\p{L}\p{M}0-9-]+', '', $dashedName);

        // Merge consecutive dashes
        return preg_replace('/-+/', '-', $sanitizedName);
    }
}
