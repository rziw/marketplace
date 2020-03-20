<?php


namespace App\Services;


use GuzzleHttp\Client;

class GeoLocationHandler
{
    private $longitude;
    private $latitude;

    public function __construct($resource, $request)
    {
        if ($resource->address != $request->address) {
            $coordinates = $this->getLocation($request);
            $this->setLongitude($coordinates[0]);
            $this->setLatitude($coordinates[1]);
        } else {
            $this->setLongitude($resource->longitude);
            $this->setLatitude($resource->latitude);
        }
    }

    /**
     * @param mixed $latitude
     */
    private function setLatitude($latitude): void
    {
        $this->latitude = $latitude;
    }

    /**
     * @param mixed $longitude
     */
    private function setLongitude($longitude): void
    {
        $this->longitude = $longitude;
    }

    /**
     * @return mixed
     */
    public function getLatitude()
    {
        return $this->latitude;
    }

    /**
     * @return mixed
     */
    public function getLongitude()
    {
        return $this->longitude;
    }

    public function getLocation($request)
    {
        $api_key = config('services.map.key');
        $client = new Client();
        $address = trim($request->address);
        $url = 'https://map.ir/search/v2/autocomplete?text=' . $address . '&$filter=province eq ' . $request->province;

        $api_result = $client->request('GET', $url, [
            'headers' => [
                'content-type' => 'application/json',
                'x-api-key' => "$api_key"
            ],

        ]);

        $decoded_result = json_decode($api_result->getBody(), true);
        $coordinates = $decoded_result["value"][0]["geom"]["coordinates"];

        return $coordinates;
    }
}
