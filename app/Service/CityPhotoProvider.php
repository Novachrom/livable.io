<?php

namespace App\Service;

use App\City;
use App\Client\TeleportApiClient;
use App\Matex\Exception;
use Illuminate\Support\Facades\Log;

class CityPhotoProvider implements CityPhotoProviderInterface
{
    /** @var TeleportApiClient */
    private $teleportClient;

    public function __construct(TeleportApiClient $teleportClient)
    {
        $this->teleportClient = $teleportClient;
    }

    public function getPhoto(City $city): ?string
    {
        try {
            $response = $this->teleportClient->getLocations($city->latitude, $city->longitude);
            $url = data_get($response, '_embedded.location:nearest-urban-areas.0._links.location:nearest-urban-area.href', null);
            if(empty($url)) {
                return null;
            }
            $url = $url.='images/';
            $response = $this->teleportClient->sendRequest($url);
            $photo = data_get($response, 'photos.0.image.mobile', null);

            return $photo;
        } catch (Exception $e) {
            Log::error($e->getMessage(), $e->getTrace());
        }

        return null;
    }
}
