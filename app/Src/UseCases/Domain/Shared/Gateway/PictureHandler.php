<?php


namespace App\Src\UseCases\Domain\Shared\Gateway;

interface PictureHandler
{
    public function add(string $path, float $width, float $height);
    public function widen(string $source, string $dest, float $width);
    public function heighten(string $source, string $dest, float $width);
    public function width(string $path);
    public function height(string $path);
    public function write(string $source, string $dest);
}
