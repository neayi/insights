<?php


namespace App\Src\UseCases\Domain\Ports;


use App\Src\Insights\Insights\Domain\Context\Context;
use App\Src\UseCases\Domain\Context\Dto\ContextDto;

interface ContextRepository
{
    public function getByUser(string $userId):?Context;
    public function add(Context $context, string $userId);
    public function update(Context $context, string $userId);
    public function getByUserDto(string $userId):?ContextDto;
}
