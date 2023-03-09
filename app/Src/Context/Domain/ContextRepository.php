<?php


namespace App\Src\Context\Domain;


use App\Src\Context\Application\Dto\ContextDto;

interface ContextRepository
{
    public function getByUser(string $userId):?Context;
    public function add(Context $context, string $userId);
    public function update(Context $context, string $userId);
    public function getByUserDto(string $userId):?ContextDto;
}
