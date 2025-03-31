<?php

declare(strict_types=1);

namespace App\Http\Controllers\Profile;

use App\Http\Controllers\Controller;
use App\Src\UseCases\Domain\Ports\GeoLocationByPostalCode;
use Illuminate\Http\Request;

class GeoLocationController extends Controller
{
    public function __invoke(Request $request, GeolocationByPostalCode $geolocationByPostalCode)
    {
        $result = $geolocationByPostalCode->getGeolocationByPostalCode(
            $request->get('country'),
            $request->get('postal_code')
        );

        return response()->json($result);
    }
}
