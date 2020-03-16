
@extends('base')

@section('head')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/gh/openlayers/openlayers.github.io@master/en/v6.2.1/css/ol.css" type="text/css">
    <style>
        .map {
            height: 600px;
            width: 100%;
        }
    </style>
    <script src="https://cdn.jsdelivr.net/gh/openlayers/openlayers.github.io@master/en/v6.2.1/build/ol.js"></script>
@endsection

@section('content')
    @parent
    <div class="filters">
        <form class="form-inline" action="{{ route('search') }}">
            <label class="sr-only" for="inlineFormInputName2">Search</label>
            <input type="text" name="q" class="form-control mb-2 mr-sm-2" id="search" placeholder="Search">

            <button type="submit" class="btn btn-primary mb-2">Search</button>
        </form>
        <form class="form-inline" id="cost-filter-form" action="{{ request()->fullUrl() }}">
            <label class="sr-only" for="inlineFormInputName2">Search</label>
            <input type="number" name="cost_of_living_from" @if(request()->has('cost_of_living_from')) value="{{ request()->get('cost_of_living_from') }}" @endif class="form-control mb-2 mr-sm-2" placeholder="Cost of living from">
            <input type="number" name="cost_of_living_to" @if(request()->has('cost_of_living_to')) value="{{ request()->get('cost_of_living_to') }}" @endif class="form-control mb-2 mr-sm-2" placeholder="Cost of living to">
            <select class="form-control mb-2 mr-sm-2" id="sort_by" name="sort_by">
                <option hidden >Sort By</option>
                <option value="name"  @if(request()->get('sort_by') == 'name') selected @endif>Name</option>
                <option value="country"  @if(request()->get('sort_by') == 'country') selected @endif>Country</option>
                <option value="cost_of_living"  @if(request()->get('sort_by') == 'cost_of_living') selected @endif>Cost of living</option>
                <option value="health_care_index"  @if(request()->get('sort_by') == 'health_care_index') selected @endif>Health Care Index</option>
                <option value="crime_index"  @if(request()->get('sort_by') == 'crime_index') selected @endif>Crime Index</option>
                <option value="traffic_time_index"  @if(request()->get('sort_by') == 'traffic_time_index') selected @endif>Traffic Time Index</option>
                <option value="quality_of_life_index"  @if(request()->get('sort_by') == 'quality_of_life_index') selected @endif>Quality of Life Index</option>
                <option value="restaurant_price_index"  @if(request()->get('sort_by') == 'restaurant_price_index') selected @endif>Restaurant Price Index</option>
                <option value="aqi">Air quality index</option>
            </select>
            <button type="submit" class="btn btn-primary mb-2">Filter</button>
        </form>
    </div>
    <ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
        <li class="nav-item">
            <a class="nav-link active" id="pills-cards-tab" data-toggle="pill" href="#pills-cards" role="tab" aria-controls="pills-cards" aria-selected="true">Cards</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" id="pills-table-tab" data-toggle="pill" href="#pills-table" role="tab" aria-controls="pills-table" aria-selected="false">Table</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" id="pills-map-tab" data-toggle="pill" href="#pills-map" role="tab" aria-controls="pills-map" aria-selected="false">Map</a>
        </li>
    </ul>
    <div class="tab-content" id="pills-tabContent">
        <div class="tab-pane fade show active" id="pills-cards" role="tabpanel" aria-labelledby="pills-cards-tab">
            @include('components.cards', ['cities' => $cities])
        </div>
        <div class="tab-pane fade" id="pills-table" role="tabpanel" aria-labelledby="pills-table-tab">
            @include('components.cities-table', ['cities' => $cities])
        </div>
        <div class="tab-pane fade" id="pills-map" role="tabpanel" aria-labelledby="pills-map-tab">
            @include('components.map', ['cities' => $cities, 'country' => $country ?? null])
        </div>
    </div>

    <div class="content">{{ $cities->links() }}</div>
    <script src="{{ url('js/main.js?v=2') }}"></script>
@endsection
