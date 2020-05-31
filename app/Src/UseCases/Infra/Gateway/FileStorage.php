<?php


namespace App\Src\UseCases\Infra\Gateway;


interface FileStorage
{
    public function setContent(string $path, array $content);
    public function content(string $path):array;
}
