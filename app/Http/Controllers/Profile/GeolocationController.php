<?php

declare(strict_types=1);

namespace App\Http\Controllers\Profile;

use App\Http\Controllers\Controller;
use App\Src\UseCases\Domain\Ports\GeoLocationByPostalCode;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class GeoLocationController extends Controller
{
    public function __invoke(Request $request, GeolocationByPostalCode $geolocationByPostalCode): JsonResponse
    {
        $country = $request->get('country');
        $postalCode = $request->get('postal_code');

        if (empty($country) || empty($postalCode)) {
            return response()->json(['error' => 'Country and postal code are required'], 400);
        }
        $result = $geolocationByPostalCode->getGeolocationByPostalCode($country, $postalCode);

        return response()->json($result);
    }
}
