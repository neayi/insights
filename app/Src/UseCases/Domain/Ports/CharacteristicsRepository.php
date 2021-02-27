<?php


namespace App\Src\UseCases\Domain\Ports;


interface CharacteristicsRepository
{
    public function getByType(string $type, bool $isMain):array;
}
