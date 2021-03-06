<?php


namespace App\Src\UseCases\Domain\Ports;


use App\Src\UseCases\Domain\Agricultural\Dto\ContextDto;
use App\Src\UseCases\Domain\Agricultural\Model\Context;

interface ContextRepository
{
    public function getByUser(string $userId);
    public function add(Context $exploitation, string $userId);
    public function getByUserDto(string $userId):?ContextDto;
}
