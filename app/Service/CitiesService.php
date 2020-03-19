<?php

namespace App\Service;

use App\City;
use App\CityAqi;
use App\Client\AqicnApiClient;
use App\Client\NumbeoApiClient;
use App\DTO\Aqicn\FeedResponse;
use App\Exceptions\AqicnApiException;
use App\Repository\CityRepository;
use App\Utils\OutputInterface;
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

    /** @var CityPhotoProviderInterface */
    private $photoProvider;

    public function __construct(NumbeoApiClient $apiClient, CityRepository $cityRepository, AqicnApiClient $aqicnApiClient, CityPhotoProviderInterface $photoProvider)
    {
        $this->numbeoApiClient = $apiClient;
        $this->cityRepository = $cityRepository;
        $this->aqicnApiClient = $aqicnApiClient;
        $this->photoProvider = $photoProvider;
    }

    public function fillNumbeoCities(): void
    {
        $cities = $this->numbeoApiClient->getCities();
        $this->cityRepository->saveNumbeoCities($cities);
    }

    public function getCititesPaginated(array $params = [], int $perPage = 100): LengthAwarePaginator
    {

        return $this->cityRepository->getCitiesPaginated($perPage, $params);
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
            $cityAqi = $city->aqi;
            try {

                if($cityAqi->is_geo) {
                    $apiResponse = $this->getAqiForLatLon((string)$city->latitude, (string)$city->longitude);
                } else {
                    $apiResponse = $this->getAqiForCity($city);
                }

                /** @var CityAqi $cityAqi */
                $cityAqi->aqi = $apiResponse->getAqi();
                $cityAqi->save();
                echo $index . ' aqi: '.$city->name.PHP_EOL;
            } catch (AqicnApiException $e) {
                echo $index . '  no aqi: '.$city->name.PHP_EOL;
                continue;

            } catch (\Exception $e) {
                Log::debug("failed fetching aqicn: " . $e->getMessage());
            }
        }
    }

    private function getAqiForCity(City $city): FeedResponse
    {
        try {
            //because us cities have states in their names, e.g. "New York, US", we need to extract city name without state
            $name = explode(',', $city->name)[0];
            $name = strtolower($name);
            $apiResponse = $this->aqicnApiClient->getDataForCity($name);

            return $apiResponse;
        } catch (AqicnApiException $e) {

           return $this->getAqiForLatLon((string)$city->latitude, (string)$city->longitude);
        }
    }

    private function getAqiForLatLon(string $lat, string $lon): FeedResponse
    {
        $apiResponse = $this->aqicnApiClient->getDataForLatLng($lat, $lon);

        return $apiResponse;
    }



    public function getCitiesForCountry(string $countryName, array $params): LengthAwarePaginator
    {
        return $this->cityRepository->getCitiesForCountry($countryName, self::CITIES_PER_PAGE, $params);
    }

    public function searchCities(string $query, array $params): LengthAwarePaginator
    {
        return $this->cityRepository->fullTextSearch($query, self::CITIES_PER_PAGE, $params);
    }

    public function importNumbeoLatLang(): void
    {
        $response = $this->numbeoApiClient->sendRequest('cities', 'get');
        $cities = $response['cities'];

        foreach ($cities as $city)
        {
            if(empty($city['latitude']) || empty($city['longitude'])) {
                continue;
            }
            $this->cityRepository->updateNumbeoLatLang((int)$city['city_id'], (float)$city['latitude'], (float)$city['longitude']);
        }
    }

    /**
     * Checks if all cities belong to one country
     *
     * @param array|Collection $cities
     * @return bool
     */
    public function isOneCountry($cities): bool
    {
        if(empty($cities)) {
            return false;
        }

        $countriesCount = collect($cities)
            ->unique('country')
            ->count();

        return $countriesCount === 1;
    }

    public function getCityDetails(string $countryName, string $cityName): ?City
    {
        return $this->cityRepository->getCityByNameAndCountry($countryName, $cityName);
    }

    public function importCityPhotos(OutputInterface $output): void
    {
        $cities = $this->cityRepository->getAllCities();
        foreach ($cities as $city) {
            $photo = $this->photoProvider->getPhoto($city);
            if (empty($photo)) {
                $output->write("city {$city->id}, {$city->name}: no photo");
                continue;
            }
            $city->photo = $photo;
            $city->save();
            $output->write("city {$city->id}, {$city->name}: photo");
        }
    }
}
