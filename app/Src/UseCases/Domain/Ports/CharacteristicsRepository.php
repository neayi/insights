<?php

declare(strict_types=1);

namespace App\Src\UseCases\Domain\Ports;

use App\Src\UseCases\Domain\Context\Model\Characteristic;

interface CharacteristicsRepository
{
    public function getByType(string $type, bool $isMain):array;
    public function getAllByType(string $type, string $countryCode):array;
    public function save(Characteristic $c);
    public function getBy(array $conditions):?Characteristic;
    public function getByPageId(int $pageId):?Characteristic;
}
