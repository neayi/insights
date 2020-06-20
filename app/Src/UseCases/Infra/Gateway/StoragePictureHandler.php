<?php


namespace App\Src\UseCases\Infra\Gateway;


use Intervention\Image\Facades\Image;

class StoragePictureHandler implements PictureHandler
{
    public function add(string $path, float $width, float $height)
    {
        Image::make(public_path('test/640*360.png'))->resize($width, $height)->save($path);
    }

    public function widen(string $source, string $dest, float $width)
    {
        $img = Image::make($source)->widen($width);
        $img->save($dest);
    }

    public function heighten(string $source, string $dest, float $height)
    {
        $img = Image::make($source)->heighten($height);
        $img->save($dest);
    }

    public function width(string $path)
    {
        return Image::make($path)->width();
    }

    public function height(string $path)
    {
        return Image::make($path)->height();
    }

    public function write(string $source, string $dest)
    {
        $img = Image::make($source);
        $img->save($dest);
    }

}
