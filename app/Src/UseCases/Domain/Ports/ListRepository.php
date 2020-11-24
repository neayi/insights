<?php


namespace App\Src\UseCases\Domain\Ports;


interface ListRepository
{
    public function getByType(string $type):array;
}
