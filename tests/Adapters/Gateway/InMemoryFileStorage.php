<?php


namespace Tests\Adapters\Gateway;


use App\Src\UseCases\Domain\Shared\Gateway\FileStorage;
use App\Src\UseCases\Domain\Shared\Gateway\PictureHandler;
use App\Src\UseCases\Domain\Shared\Model\Picture;

class InMemoryFileStorage implements FileStorage
{
    private $fs = [];

    public function setContent(string $path, array $content)
    {
        $this->fs[$path] = $content;
    }

    public function content(string $path): array
    {
        return $this->fs[$path];
    }

    public function uriToTmpPicture(string $uri): Picture
    {
        $path = uniqid();
        app(PictureHandler::class)->add($path, 600, 600);
        return new Picture($path, $ext = 'jpg');
    }

}
