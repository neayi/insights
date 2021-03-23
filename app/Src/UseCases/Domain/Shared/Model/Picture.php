<?php


namespace App\Src\UseCases\Domain\Shared\Model;



use App\Src\UseCases\Domain\Shared\Gateway\PictureHandler;

class Picture
{
    private $path;
    private $relativePath;
    private $ext;

    public function __construct(string $path, string $ext = null)
    {
        $this->path = $path;
        $this->ext = $ext;
    }

    public function resize(string $newPath)
    {
        $relativePath = $newPath;
        $finalNewPath = storage_path().'/'.$newPath;
        if(isset($this->ext)){
            $finalNewPath = storage_path().'/'.$newPath.'.'.$this->ext;
            $relativePath = $newPath.'.'.$this->ext;
        }
        if(app(PictureHandler::class)->width($this->path) > 600) {
            app(PictureHandler::class)->widen($this->path, $finalNewPath, 600);
            $this->relativePath = $relativePath;
            return;
        }

        if(app(PictureHandler::class)->height($this->path) > 400) {
            app(PictureHandler::class)->heighten($this->path, $finalNewPath, 400);
            $this->relativePath = $relativePath;
            return;
        }

        app(PictureHandler::class)->write($this->path, $finalNewPath);
        $this->relativePath = $relativePath;
    }

    public function relativePath(): string
    {
        return $this->relativePath;
    }
}
