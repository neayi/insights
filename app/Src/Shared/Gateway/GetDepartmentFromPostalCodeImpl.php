<?php


namespace App\Src\Shared\Gateway;


use GuzzleHttp\Client;

class GetDepartmentFromPostalCodeImpl implements GetDepartmentFromPostalCode
{
    private $httpClient;
    private $api = 'https://api-adresse.data.gouv.fr/search/?q=';

    public function __construct()
    {
        $this->httpClient = new Client();
    }


    public function execute(string $postalCode)
    {
        $response = $this->httpClient->get($this->api.$postalCode);
        $content = json_decode($response->getBody()->getContents(), true);

        $coordinates = null;
        $departmentNumber = null;

        $features = $content['features'];
        if(isset($features) && !empty($features)){
            $feature = $features[0];
            $coordinates = $feature['geometry']['coordinates'];
            $departmentNumber = explode(',', $feature['properties']['context'])[0];
        }

        return [
            'coordinates' => $coordinates,
            'department_number' => $departmentNumber
        ];
    }
}
