<?php

namespace App\Service;

use App\Client\OecdApiClient;
use App\Repository\CountryRepository;

class CountriesService
{
    /** @var OecdApiClient */
    private $oecdApiClient;

    /** @var CountryRepository */
    private $countryRepository;

    public function __construct(OecdApiClient $oecdApiClient, CountryRepository $countryRepository)
    {
        $this->oecdApiClient = $oecdApiClient;
        $this->countryRepository = $countryRepository;
    }

    public function fillOecdBliData()
    {
        $data = $this->oecdApiClient->getBliData();
        foreach ($data as $countryName => $item) {
            $country = $this->countryRepository->getCountryByName($countryName);
            if(null === $country) {
                continue;
            }
            $country->oecd_bli_data = $item;
            $country->save();
        }
    }
}
