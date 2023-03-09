<?php


namespace App\Src\Shared\Gateway;


use App\Src\Shared\Model\Picture;

interface FileStorage
{
    public function setContent(string $path, array $content);
    public function content(string $path):array;
    public function uriToTmpPicture(string $uri): Picture;
}
