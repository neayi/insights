<?php


namespace App\Http\Controllers\Api;

use App\Src\UseCases\Domain\Users\GetAvatar;
use Illuminate\Routing\Controller as BaseController;

class UserController extends BaseController
{
    public function avatar(string $uuid, string $width, GetAvatar $getAvatar)
    {
        return $getAvatar->execute($uuid, $width);
    }
}
