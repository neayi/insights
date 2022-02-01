<?php


namespace App\Http\Controllers\Api;

use App\Src\UseCases\Domain\Context\Queries\GetContextByUser;
use App\Src\UseCases\Domain\Users\GetAvatar;
use App\User;
use Illuminate\Routing\Controller as BaseController;

/**
 * @group User management
 *
 * APIs for managing users
 */
class UserController extends BaseController
{

    /**
     * Serve the avatar of the user
     * @urlParam id string required The user uuid Example:379189d0-287f-4042-bf81-577deb7696f4
     * @urlParam dim integer required Width of the picture in pixels Example:300
     */
    public function avatar(string $uuid, int $width, GetAvatar $getAvatar)
    {
        return $getAvatar->execute($uuid, $width);
    }

    /**
     * Serve the avatar of the user for discourse
     * @urlParam id string required The user uuid Example:379189d0-287f-4042-bf81-577deb7696f4
     * @urlParam dim integer required Width of the picture in pixels Example:300
     */
    public function avatarDiscourse(string $username, string $firstLetter, string $color, int $width, GetAvatar $getAvatar)
    {
        $user = User::query()->where("discourse_username", $username)->first();
        if($user === null){
            return null;
        }
        $avatar = $getAvatar->execute($user->uuid, $width, true);
        if($avatar === null){
            return redirect(config('neayi.forum_url').'/letter_avatar_proxy/v4/letter/'.$firstLetter.'/'.$color.'/'.$width.'.png');
        }
        return $avatar;
    }

    /**
     * Get the context of a user
     * @urlParam id string required the user uuid Example:379189d0-287f-4042-bf81-577deb7696f4
     */
    public function context(string $uuid, GetContextByUser $contextQueryByUser)
    {
        return $contextQueryByUser->execute($uuid);
    }
}
