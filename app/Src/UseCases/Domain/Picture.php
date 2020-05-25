<?php


namespace App\Src\UseCases\Domain;


use App\Src\UseCases\Infra\Gateway\PictureHandler;

class Picture
{
    private $path;

    public function __construct(string $path)
    {
        $this->path = $path;
    }

    public function resize(string $newPath)
    {
        if(app(PictureHandler::class)->width($this->path) > 600) {
            app(PictureHandler::class)->widthen($newPath, 600);
            return;
        }

        if(app(PictureHandler::class)->height($this->path) > 400) {
            app(PictureHandler::class)->heighten($newPath, 400);
            return;
        }

        app(PictureHandler::class)->write($this->path, $newPath);
    }
}
