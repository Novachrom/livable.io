<?php

namespace App\Repository;

use App\City;
use App\Country;
use App\DTO\Numbeo\City as NumbeoCityDTO;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Collection;

class CityRepository
{
    /**
     * @param NumbeoCityDTO[] $cities
     */
    public function saveNumbeoCities(array $cities): void
    {
        $countriesCache = [];
        foreach ($cities as $city) {
            if(isset($countriesCache[$city->getCountryName()])) {
                $country = $countriesCache[$city->getCountryName()];
            } else {
                $country = Country::query()
                    ->firstOrCreate(['name' => $city->getCountryName()]);
                $countriesCache[$city->getCountryName()] = $country;
            }

            $cityEntity = City::query()
                ->firstOrNew(['numbeo_city_id' => $city->getCityId()]);

            $cityEntity->name = $city->getName();
            $cityEntity->cost_of_living = $city->getCostOfLiving();
            $cityEntity->health_care_index = $city->getHealthCareIndex();
            $cityEntity->crime_index = $city->getCrimeIndex();
            $cityEntity->traffic_time_index = $city->getTrafficTimeIndex();
            $cityEntity->quality_of_life_index = $city->getQualityOfLifeIndex();
            $cityEntity->restaurant_price_index = $city->getRestaurantPriceIndex();
            $cityEntity->country_id = $country->id;

            $cityEntity->save();
        }
    }

    public function getCitiesPaginated(int $perPage): LengthAwarePaginator
    {
        return City::with('country', 'aqi')
            ->paginate($perPage);
    }

    public function getCitiesWithEmptyIndexes(): Collection
    {
        return City::query()
            ->where('cost_of_living', 0)
            ->where('health_care_index', 0)
            ->where('crime_index', 0)
            ->where('traffic_time_index', 0)
            ->where('quality_of_life_index', 0)
            ->where('restaurant_price_index', 0)
            ->get();
    }

    /**
     * @return \Generator<City>
     */
    public function getAllCities(): \Generator
    {
        /** @var City $city */
        foreach (City::query()->with('aqi')->get() as $city) {
            yield $city;
        }
    }

    /**
     * @param string $countryName
     * @return Collection<City>
     */
    public function getCitiesForCountry(string $countryName, int $perPage): LengthAwarePaginator
    {
        return City::query()->with('country', 'aqi')
            ->select('cities.*')
            ->join('countries', 'cities.country_id', '=', 'countries.id')
            ->where('countries.name', $countryName)
            ->paginate();
    }
}
