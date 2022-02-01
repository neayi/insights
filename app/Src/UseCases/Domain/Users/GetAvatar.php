<?php


namespace App\Src\UseCases\Domain\Users;


use App\Src\UseCases\Domain\Ports\UserRepository;
use App\Src\UseCases\Domain\User;
use Intervention\Image\Facades\Image;

class GetAvatar
{
    private $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function execute(string $uuid, int $dim, bool $noDefault = false)
    {
        $user = $this->userRepository->getById($uuid);
        $pathPicture = $this->getPathPicture($user, $noDefault);

        if($pathPicture === null){
            return null;
        }

        $img = Image::make($pathPicture);
        $h = $img->height();
        $w = $img->width();

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
        }, 3600, true);

        return $img->response();

    }


    private function getPathPicture(?User $user, bool $noDefault = false): ?string
    {
        if (isset($user) && $user->toArray()['path_picture'] !== null && $user->toArray()['path_picture'] !== "") {
            return storage_path($user->toArray()['path_picture']);
        }
        if($noDefault === true){
            return null;
        }
        return public_path(config('neayi.default_avatar'));
    }
}
