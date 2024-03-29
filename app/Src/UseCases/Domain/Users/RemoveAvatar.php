<?php


namespace App\Src\UseCases\Domain\Users;


use App\User;

class RemoveAvatar
{
    public function execute(string $uuid)
    {
        $user = User::query()->where('uuid', $uuid)->first();
        if(!isset($user)){
            return null;
        }
        $pathPicture = $user->path_picture;
        $user->path_picture = '';
        $user->save();

        unlink(storage_path($pathPicture));
    }
}
