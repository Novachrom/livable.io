<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CityAqi extends Model
{
    protected $table = 'city_aqi';

    protected $fillable = ['*'];

    public function city()
    {
        return $this->belongsTo(City::class, 'city_id', 'id');
    }

    public function getAqiAttribute($value)
    {
        return $value ?? 'N/A';
    }
}
