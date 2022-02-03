<?php


namespace App\Src\UseCases\Domain\Users;


use App\Src\UseCases\Domain\Ports\UserRepository;
use App\Src\UseCases\Domain\User;
use Intervention\Image\Facades\Image;
use Laravolt\Avatar\Facade as Avatar;

class GetAvatar
{
    private $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function execute(string $uuid, int $dim, bool $noDefault = false, string $firstLetter = null, string $color = null)
    {
        $user = $this->userRepository->getById($uuid);

        if ($noDefault === true)
            $pathPicture = null;
        else
            $pathPicture = public_path(config('neayi.default_avatar'));

        if (!empty($user))
            $pathPicture = $this->getPathPicture($user, $noDefault);

        if($pathPicture === null){
            if (empty($firstLetter) && !empty($user))
                $firstLetter = $user->fullname();

            if (!empty($color))
                return Avatar::create($firstLetter)->setBackground('#' . $color)->getImageObject()->response();
            else
                return Avatar::create($firstLetter)->getImageObject()->response();
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
