<?php


namespace App\Services;


use GuzzleHttp\Client;

class GeoLocationHandler
{
    private $longtitude;
    private $latitude;

    public function __construct($resource, $request)
    {
        if ($resource->address != $request->address) {
            $cordinates = $this->getLocation($resource, $request);
            $this->setLongtitude($cordinates[0]);
            $this->setLatitude($cordinates[1]);
        } else {
            $this->setLongtitude($resource->longtitude);
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
     * @param mixed $longtitude
     */
    private function setLongtitude($longtitude): void
    {
        $this->longtitude = $longtitude;
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
        return $this->longtitude;
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
