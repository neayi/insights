<?php


namespace App\Src\UseCases\Domain\Context\Queries;


use App\Src\Utils\Image\ScaledImageResponse;
use Intervention\Image\Laravel\Facades\Image;

class GetIcon
{
    public function execute(string $uuid, ?int $dim)
    {
        $pathPicture = storage_path('app/public/characteristics/'.$uuid.'.png');

        // Sometimes there's no picture for the characteristic. Return an empty pixel.
        if (!file_exists($pathPicture))
            $pathPicture = app_path('../public/images/empty-pixel.gif');
        
        if($dim == null){
            return response()->image(Image::read($pathPicture));
        }

        return ScaledImageResponse::make($pathPicture, $dim, 86400);
    }

}
