<?php


namespace App\Http\Controllers\Api;

use App\Src\UseCases\Domain\Context\Queries\GetIcon;
use Illuminate\Routing\Controller as BaseController;

class PictureController extends BaseController
{
    /**
     * @urlParam id string required The uuid of the characteristics Example:0a581bd9-3e63-4ee9-9246-59b54b760bda
     * @urlParam dim integer Width of the picture in pixels Example:300
     */
    public function serve(string $uuid, ?int $dim = null, GetIcon $getIcon)
    {
        return $getIcon->execute($uuid, $dim);
    }
}
