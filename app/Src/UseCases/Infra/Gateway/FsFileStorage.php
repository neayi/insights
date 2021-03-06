<?php


namespace App\Src\UseCases\Infra\Gateway;


use App\Src\UseCases\Domain\Shared\Gateway\FileStorage;
use App\Src\UseCases\Domain\Shared\Model\Picture;

class FsFileStorage implements FileStorage
{
    public function setContent(string $path, array $content)
    {
        // TODO: Implement setContent() method.
    }

    public function content(string $path): array
    {
        // TODO: Implement content() method.
    }

    public function uriToTmpPicture(string $uri): Picture
    {
        $content = file_get_contents($uri);
        $ext = 'jpg';
        $ph = fopen($path = sys_get_temp_dir().'/'.uniqid(), 'w+');
        fwrite($ph, $content);
        return new Picture($path, $ext);
    }

}
