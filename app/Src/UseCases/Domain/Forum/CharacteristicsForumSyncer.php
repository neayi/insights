<?php

declare(strict_types=1);

namespace App\Src\UseCases\Domain\Forum;

use App\Src\UseCases\Domain\Context\Model\Characteristic;

interface CharacteristicsForumSyncer
{
    /**
     * @param string $type
     * @param string $locale
     * @param Characteristic[] $characteristics
     */
    public function syncCharacteristicTagGroup(string $type, string $locale, array $characteristics): void;
}