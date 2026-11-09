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
    // Used when the size in the URL is not a number, e.g. a client that requests
    // Discourse's avatar template without replacing its {size} placeholder.
    private const DEFAULT_AVATAR_WIDTH = 120;

    /**
     * Serve the avatar of the user
     * @urlParam id string required The user uuid Example:379189d0-287f-4042-bf81-577deb7696f4
     * @urlParam dim integer required Width of the picture in pixels Example:300
     */
    public function avatar(string $uuid, string $width, GetAvatar $getAvatar)
    {
        return $getAvatar->execute($uuid, $this->parseWidth($width));
    }

    /**
     * Serve the avatar of the user for discourse
     * @urlParam id string required The user uuid Example:379189d0-287f-4042-bf81-577deb7696f4
     * @urlParam dim integer required Width of the picture in pixels Example:300
     */
    public function avatarDiscourse(string $username, string $firstLetter, string $color, string $width, GetAvatar $getAvatar)
    {
        $uuid = '';
        $user = User::query()->whereHas('discourseProfiles', fn($query) => $query->where("username", $username))->first();
        if(!empty($user)){
            $uuid = $user->uuid;
        }
        $avatar = $getAvatar->execute($uuid, $this->parseWidth($width), true, $firstLetter, $color);

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

    private function parseWidth(string $width): int
    {
        return ctype_digit($width) ? (int) $width : self::DEFAULT_AVATAR_WIDTH;
    }
}
