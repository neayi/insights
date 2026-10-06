<?php


namespace App\Src\UseCases\Infra\Gateway;


use App\Src\UseCases\Domain\Shared\Gateway\PictureHandler;
use Intervention\Image\Laravel\Facades\Image;

class StoragePictureHandler implements PictureHandler
{
    public function add(string $path, float $width, float $height)
    {
        Image::read(public_path('test/640*360.png'))->resize((int) $width, (int) $height)->save($path);
    }

    public function widen(string $source, string $dest, float $width)
    {
        $img = Image::read($source)->scale(width: (int) $width);
        $img->save($dest);
    }

    public function heighten(string $source, string $dest, float $height)
    {
        $img = Image::read($source)->scale(height: (int) $height);
        $img->save($dest);
    }

    public function width(string $path)
    {
        return Image::read($path)->width();
    }

    public function height(string $path)
    {
        return Image::read($path)->height();
    }

    public function write(string $source, string $dest)
    {
        $img = Image::read($source);
        $img->save($dest);
    }

}
