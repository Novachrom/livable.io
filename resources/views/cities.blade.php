
@extends('base')

@section('content')
    @parent
    <table class="table table-striped">
        <thead>
        <tr>
            <th scope="col">#</th>
            <th scope="col">City</th>
            <th scope="col">Country</th>
            <th scope="col">Cost of living</th>
            <th scope="col">Health Care Index</th>
            <th scope="col">Crime Index</th>
            <th scope="col">Traffic Time Index</th>
            <th scope="col">Quality of Life Index</th>
            <th scope="col">Restaurant Price Index</th>
            <th scope="col">Air quality index</th>
        </tr>
        </thead>
        <tbody>
        @foreach($cities as $index => $city)
            <tr>
                <th scope="row">{{$index+1}}</th>
                <td>{{$city->name}}</td>
                <td>{{$city->country->name}}</td>
                <td>{{$city->cost_of_living}}</td>
                <td>{{$city->health_care_index}}</td>
                <td>{{$city->crime_index}}</td>
                <td>{{$city->traffic_time_index}}</td>
                <td>{{$city->quality_of_life_index}}</td>
                <td>{{$city->restaurant_price_index}}</td>
                <td>{{$city->aqi->aqi}}</td>
            </tr>
        @endforeach
        </tbody>
    </table>
    <div class="content">{{ $cities->links() }}</div>
@endsection
