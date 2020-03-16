<?php

namespace App\Repository;

use App\City;
use App\Country;
use App\Decorator\QueryDecoratorCollection;
use App\DTO\Numbeo\City as NumbeoCityDTO;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class CityRepository
{
    /** @var QueryDecoratorCollection */
    private $queryDecorators;

    public function __construct(QueryDecoratorCollection $queryDecorators)
    {
        $this->queryDecorators = $queryDecorators;
    }

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
            $cityEntity->latitude = $city->getLatitude();
            $cityEntity->longitude = $city->getLongitude();

            $cityEntity->save();
        }
    }

    public function getCitiesPaginated(int $perPage, array $params): LengthAwarePaginator
    {
        $query = City::with('country', 'aqi');

        return $this->queryDecorators
            ->decorate($query, $params)
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
    public function getCitiesForCountry(string $countryName, int $perPage, array $params): LengthAwarePaginator
    {
        $query = City::query()->with('country', 'aqi')
            ->select('cities.*')
            ->join('countries', 'cities.country_id', '=', 'countries.id')
            ->where('countries.name', $countryName);

        return $this->queryDecorators
            ->decorate($query, $params)
            ->paginate($perPage);
    }

    public function fullTextSearch(string $searchQuery, int $perPage, array $params): LengthAwarePaginator
    {
        /** @var Builder $query */
        $query = City::search($searchQuery);

        return $this->queryDecorators
            ->decorate($query, $params)
            ->paginate($perPage);
    }

    public function updateNumbeoLatLang(int $numbeoCityId, float $lat, float $lng): int
    {
        return DB::table('cities')
            ->where('numbeo_city_id', $numbeoCityId)
            ->update(['longitude' => $lng, 'latitude' => $lat]);
    }

    public function getCityByNameAndCountry(string $countryName, string $cityName): ?City
    {
        return City::query()->with('country', 'aqi', 'customCalculations')
            ->select('cities.*')
            ->join('countries', 'cities.country_id', '=', 'countries.id')
            ->where('countries.name', $countryName)
            ->where('cities.name', $cityName)
            ->first();
    }

    public function getAvavilableVariables(): array
    {
        /** @var City $city */
        $city = City::query()->first();
        $variables = $city->getAvailableVariables();

        return array_keys($variables);
    }
}
