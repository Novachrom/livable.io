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
}
