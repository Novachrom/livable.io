<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Nicolaslopezj\Searchable\SearchableTrait;

class City extends Model
{
    use SoftDeletes;
    use SearchableTrait;

    protected $searchable = [
        'columns' => [
            'cities.name' => 10,
            'countries.name' => 5
        ],
        'joins' => [
            'countries' => ['cities.country_id', 'countries.id']
        ]
    ];

    protected $fillable = ['numbeo_city_id'];

    public function country()
    {
        return $this->belongsTo(Country::class, 'country_id', 'id');
    }

    public function aqi()
    {
        return $this->hasOne(CityAqi::class, 'city_id', 'id')->withDefault();
    }

    public function getCostOfLivingWithCurrency(): string
    {
        $currency = $this->country->currency;
        if(empty($currency) || $currency->code === 'USD') {
            return $this->cost_of_living . ' USD';
        }

        $value = $this->cost_of_living * $currency->usd_rate;

        return $value . " {$currency->code}";
    }

    public function customCalculations()
    {
        return $this
            ->belongsToMany(CustomCalculation::class, 'city_calculations', 'city_id', 'calculation_id')
            ->withPivot('value');
    }

    public function getAvailableVariables(): array
    {
        $res = [
            'cost_of_living' => $this->cost_of_living,
            'health_care_index' => $this->health_care_index,
            'crime_index' => $this->crime_index,
            'traffic_time_index' => $this->traffic_time_index,
            'quality_of_life_index' => $this->quality_of_life_index,
            'restaurant_price_index' => $this->restaurant_price_index,
            'aqi' => data_get($this, 'aqi.aqi', 0)
        ];
        foreach ($this->customCalculations as $calculation) {
            $res[$calculation->var_name] = $calculation->pivot->value;
        }

        return $res;
    }
}
