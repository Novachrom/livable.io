<?php

namespace App\Service;

use App\City;
use App\CityAqi;
use App\Client\AqicnApiClient;
use App\Client\NumbeoApiClient;
use App\Exceptions\AqicnApiException;
use App\Repository\CityRepository;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;

class CitiesService
{
    private const CITIES_PER_PAGE = 50;

    /** @var NumbeoApiClient */
    private $numbeoApiClient;

    /** @var CityRepository */
    private $cityRepository;

    /** @var AqicnApiClient */
    private $aqicnApiClient;

    public function __construct(NumbeoApiClient $apiClient, CityRepository $cityRepository, AqicnApiClient $aqicnApiClient)
    {
        $this->numbeoApiClient = $apiClient;
        $this->cityRepository = $cityRepository;
        $this->aqicnApiClient = $aqicnApiClient;
    }

    public function fillNumbeoCities(): void
    {
        $cities = $this->numbeoApiClient->getCities();
        $this->cityRepository->saveNumbeoCities($cities);
    }

    public function getCititesPaginated(int $perPage = 100): LengthAwarePaginator
    {
        return $this->cityRepository->getCitiesPaginated($perPage);
    }

    public function removeEmptyNumbeoCities(): void
    {
        $emptyCities = $this->cityRepository->getCitiesWithEmptyIndexes();
        /** @var City $city */
        foreach ($emptyCities as $city) {
            $city->delete();
        }
    }

    public function fillAqi()
    {
        /** @var City $city */
        foreach ($this->cityRepository->getAllCities() as $index => $city)
        {
            try {
                //because us cities have states in their names, e.g. "New York, US", we need to extract city name without state
                $name = explode(',', $city->name)[0];
                $name = strtolower($name);
                $apiResponse = $this->aqicnApiClient->getDataForCity($name);

                /** @var CityAqi $cityAqi */
                $cityAqi = $city->aqi;
                $cityAqi->aqi = $apiResponse->getAqi();
                $cityAqi->save();
                echo $index . ' aqi: '.$city->name.PHP_EOL;
            } catch (AqicnApiException $e) {
                echo $index . ' no aqi: '.$city->name.PHP_EOL;
                continue;
            } catch (\Exception $e) {
                Log::debug("failed fetching apicn: " . $e->getMessage());
            }
        }
    }


    public function getCitiesForCountry(string $countryName): LengthAwarePaginator
    {
        return $this->cityRepository->getCitiesForCountry($countryName, self::CITIES_PER_PAGE);
    }

    public function searchCities(string $query): LengthAwarePaginator
    {
        return $this->cityRepository->fullTextSearch($query, self::CITIES_PER_PAGE);
    }
}
