<?php


namespace App\Src\UseCases\Domain\Ports;


use App\Src\UseCases\Domain\Context\Dto\ContextDto;
use App\Src\UseCases\Domain\Context\Model\Context;

interface ContextRepository
{
    public function getByUser(string $userId);
    public function add(Context $context, string $userId);
    public function update(Context $context, string $userId);
    public function getByUserDto(string $userId):?ContextDto;
}
