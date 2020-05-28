<?php


namespace App\Src\UseCases\Infra\Gateway;


class InMemoryPictureHandler implements PictureHandler
{
    private $pictures = [];

    public function add(string $path, float $width, float $height)
    {
        $this->pictures[$path]['width'] = $width;
        $this->pictures[$path]['height'] = $height;
    }

    public function widen(string $source, string $dest, float $width)
    {
        $this->pictures[$dest]['width'] = $width;
    }

    public function heighten(string $source, string $dest, float $height)
    {
        $this->pictures[$dest]['height'] = $height;
    }

    public function width(string $path)
    {
        return $this->pictures[$path]['width'];
    }

    public function height(string $path)
    {
        return $this->pictures[$path]['height'];
    }

    public function write(string $source, string $dest)
    {
        $this->pictures[$dest] = $this->pictures[$source];
    }
}
