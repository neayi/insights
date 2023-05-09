<?php

declare(strict_types=1);

namespace App\Http\Controllers\Profile;

use App\GeoOpenDataSoftService;
use App\Http\Controllers\Controller;

class GeoController extends Controller
{
    public function __invoke(GeoOpenDataSoftService $geoOpenDataSoftService)
    {
        $postalCode = request()->get('postal_code');
        $result = $geoOpenDataSoftService->getGeolocationByPostalCode($postalCode);

        $results = [];
        foreach($result['records'] as $record) {
            $results[$record['fields']['country_code']][] = $record;
        }
        return view('users.wizard-profile.fill-postal-code-details', ['geos' => $results]);
    }
}
