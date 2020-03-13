@extends('base')

@section('content')
    <ul class="list-group">
        <li class="list-group-item">Name: {{ $city->name }}</li>
        <li class="list-group-item">Country: {{$city->country->name}}</li>
        <li class="list-group-item">Cost of living: {{$city->getCostOfLivingWithCurrency()}}</li>
        <li class="list-group-item">Health care index: {{$city->health_care_index}}</li>
        <li class="list-group-item">Crime index: {{$city->crime_index}}</li>
        <li class="list-group-item">Traffic time index: {{$city->traffic_time_index}}</li>
        <li class="list-group-item">Quality of life index: {{$city->quality_of_life_index}}</li>
        <li class="list-group-item">Restaurant price index: {{$city->restaurant_price_index}}</li>
        <li class="list-group-item">Air quality index: {{$city->aqi->aqi}}</li>
    </ul>
@endsection
