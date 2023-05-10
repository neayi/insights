<?php

declare(strict_types=1);

namespace App\Http\Controllers\Profile;

use App\GeoOpenDataSoftService;
use App\Http\Controllers\Controller;
use Tariq86\CountryList\CountryList;

class GeoController extends Controller
{
    public function __invoke(GeoOpenDataSoftService $geoOpenDataSoftService)
    {
        $cl = new CountryList();
        $postalCode = request()->get('postal_code');
        $result = $geoOpenDataSoftService->getGeolocationByPostalCode($postalCode);

        $results = [];
        foreach($result['records'] as $record) {
            try {
                $countryTrans = $cl->getOne($record['fields']['country_code'], 'fr');
            } catch (\Throwable $e) {
                $countryTrans = $record['fields']['country_code'];
            } finally {
                $record['fields']['country_trans'] = $countryTrans;
            }
            $results[$countryTrans][] = $record;
        }
        return view('users.wizard-profile.fill-postal-code-details', ['geos' => $results]);
    }
}
