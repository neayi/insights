<?php


namespace App\Src\UseCases\Domain\Context\Queries;


use Intervention\Image\Facades\Image;

class GetIcon
{
    public function execute(string $uuid, ?int $dim)
    {
        $pathPicture = storage_path('app/public/characteristics/'.$uuid.'.png');

        // Sometimes there's no picture for the characteristic. Return an empty pixel.
        if (!file_exists($pathPicture))
            $pathPicture = app_path('../public/images/empty-pixel.gif');
        
        $img = Image::make($pathPicture);
        $h = $img->height();
        $w = $img->width();

        if($dim == null){
            return $img->response();
        }

        $img = Image::cache(function($image) use($pathPicture, $dim, $w, $h){
            if($w <= $h) {
                $image->make($pathPicture)->widen($dim, function ($constraint) {
                    $constraint->upsize();
                });
            }else{
                $image->make($pathPicture)->heighten($dim, function ($constraint) {
                    $constraint->upsize();
                });
            }
        }, 86400, true);
        return $img->response();
    }

}
