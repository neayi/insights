<?php


namespace App\Src\UseCases\Domain\Users;


use App\Src\UseCases\Domain\Ports\UserRepository;
use App\Src\UseCases\Domain\User;
use App\Src\Utils\Image\ScaledImageResponse;
use Intervention\Image\Format;
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
                $firstLetter = $user->fullname;

            $avatar = Avatar::create($firstLetter);
            if (!empty($color))
                $avatar->setBackground('#' . $color);

            return response()->image($avatar->getImageObject(), Format::PNG);
        }

        return ScaledImageResponse::make($pathPicture, $dim, 3600);
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
