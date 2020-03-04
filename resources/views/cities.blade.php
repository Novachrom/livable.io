
@extends('base')

@section('head')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/gh/openlayers/openlayers.github.io@master/en/v6.2.1/css/ol.css" type="text/css">
    <style>
        .map {
            height: 400px;
            width: 100%;
        }
    </style>
    <script src="https://cdn.jsdelivr.net/gh/openlayers/openlayers.github.io@master/en/v6.2.1/build/ol.js"></script>
@endsection

@section('content')
    @parent

    @include('components.cities-table', ['cities' => $cities])
    @includeUnless(empty($country), 'components.map', ['cities' => $cities, 'country' => $country])
    <script src="{{ url('js/main.js') }}"></script>
@endsection
