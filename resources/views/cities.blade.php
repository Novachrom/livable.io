
@extends('base')

@section('content')
    @parent
    <form class="form-inline" action="{{ request()->fullUrl() }}">
        <label class="sr-only" for="inlineFormInputName2">Search</label>
        <input type="number" name="cost_of_living_from" @if(request()->has('cost_of_living_from')) value="{{ request()->get('cost_of_living_from') }}" @endif class="form-control mb-2 mr-sm-2" id="search" placeholder="Cost of living from">
        <input type="number" name="cost_of_living_to" @if(request()->has('cost_of_living_to')) value="{{ request()->get('cost_of_living_to') }}" @endif class="form-control mb-2 mr-sm-2" id="search" placeholder="Cost of living to">

        <button type="submit" class="btn btn-primary mb-2">Filter</button>
    </form>
    <table class="table table-striped">
        <thead>
        <tr>
            <th scope="col">#</th>
            <th scope="col"><a href="{{ request()->fullUrlWithQuery(['sort_by' => 'name']) }}">City</a></th>
            <th scope="col"><a href="{{ request()->fullUrlWithQuery(['sort_by' => 'country']) }}">Country</a></th>
            <th scope="col"><a href="{{ request()->fullUrlWithQuery(['sort_by' => 'cost_of_living']) }}">Cost of living</a></th>
            <th scope="col"><a href="{{ request()->fullUrlWithQuery(['sort_by' => 'health_care_index']) }}">Health Care Index</a></th>
            <th scope="col"><a href="{{ request()->fullUrlWithQuery(['sort_by' => 'crime_index']) }}">Crime Index</a></th>
            <th scope="col"><a href="{{ request()->fullUrlWithQuery(['sort_by' => 'traffic_time_index']) }}">Traffic Time Index</a></th>
            <th scope="col"><a href="{{ request()->fullUrlWithQuery(['sort_by' => 'quality_of_life_index']) }}">Quality of Life Index</a></th>
            <th scope="col"><a href="{{ request()->fullUrlWithQuery(['sort_by' => 'restaurant_price_index']) }}">Restaurant Price Index</a></th>
            <th scope="col"><a href="{{ request()->fullUrlWithQuery(['sort_by' => 'aqi']) }}">Air quality index</a></th>
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
