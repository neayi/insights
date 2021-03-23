<?php


namespace App\Http\Controllers\Api;

use App\Src\UseCases\Domain\Agricultural\Queries\GetIcon;
use Illuminate\Routing\Controller as BaseController;

class PictureController extends BaseController
{
    public function serve(string $uuid, ?int $dim = null, GetIcon $getIcon)
    {
        return $getIcon->execute($uuid, $dim);
    }
}
