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

    private function setLatitude(float $latitude): void
    {
        $this->latitude = $latitude;
    }

    private function setLongitude(float $longitude): void
    {
        $this->longitude = $longitude;
    }

    public function getLatitude(): float
    {
        return $this->latitude;
    }

    public function getLongitude(): float
    {
        return $this->longitude;
    }

    public function getLocation($request): array
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
