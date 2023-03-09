<?php


namespace App\Src\Context\Domain;


interface CharacteristicsRepository
{
    public function getByType(string $type, bool $isMain):array;
    public function getAllByType(string $type):array;
    public function save(Characteristic $c);
    public function getBy(array $conditions):?Characteristic;
    public function getByPageId(int $pageId):?Characteristic;
}
