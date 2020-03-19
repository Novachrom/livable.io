<?php

namespace App\Service;

use App\City;

interface CityPhotoProviderInterface
{
    public function getPhoto(City $city): ?string ;
}
