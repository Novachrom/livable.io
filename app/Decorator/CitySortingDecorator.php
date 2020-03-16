<?php

namespace App\Decorator;

use Illuminate\Database\Eloquent\Builder;

class CitySortingDecorator implements QueryDecorator
{
    private const FIELD_NAME = 'sort_by';

    private const DEFAULT_SORT_BY = 'quality_of_life_index';

    private const ALLOWED_VALUES = [
        'name',
        'country',
        'cost_of_living',
        'health_care_index',
        'crime_index',
        'traffic_time_index',
        'quality_of_life_index',
        'restaurant_price_index',
        'aqi'
    ];

    public function decorate(Builder $query, array $params): Builder
    {
        if(!isset($params[self::FIELD_NAME])) {
            $value = self::DEFAULT_SORT_BY;
        } else {
            $value = $params[self::FIELD_NAME];
        }

        if(!in_array($value, self::ALLOWED_VALUES)) {
            return $query;
        }

        if($value === 'country') {
            return $this->orderByCountry($query);
        }

        if($value === 'aqi') {
            return $this->orderByAqi($query);
        }

        return $query->orderBy($value);
    }

    private function orderByCountry(Builder $query): Builder
    {
        if($this->isJoined($query, 'countries')) {
            return $query->orderBy('countries.name');
        }

        return $query
            ->join('countries', 'cities.country_id', '=', 'countries.id')
            ->select('cities.*')
            ->orderBy('countries.name');
    }

    private function orderByAqi(Builder $query): Builder
    {
        if($this->isJoined($query, 'city_aqi')) {
            return $query->orderBy('city_aqi.aqi');
        }

        return $query
            ->leftJoin('city_aqi', 'cities.id', '=', 'city_aqi.city_id')
            ->select('cities.*')
            ->orderBy('city_aqi.aqi');
    }

    private function isJoined(Builder $query, $table): bool
    {
        $joins = collect($query->getQuery()->joins);
        return $joins->pluck('table')->contains($table);
    }
}
