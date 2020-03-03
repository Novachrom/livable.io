
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

    <form class="form-inline" id="cost-filter-form" action="{{ request()->fullUrl() }}">
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
    <div id="map" class="map"></div>
    <div id="popup" class="ol-popup" style="display:none">
        <div class="card" style="width: 18rem;">
            <div class="card-body">
                <h5 class="card-title" id="popup-title">City</h5>
                <p class="card-text" id="popup-content">Some quick example text to build on the card title and make up the bulk of the card's content.</p>
                <a href="#" class="btn btn-danger" id="popup-closer">Close</a>
            </div>
        </div>
    </div>
    <script type="text/javascript">

        var cities = {!!  json_encode($cities) !!};
        // console.log(cities);
        function createMap(locations) {

            var mapCenter = ol.proj.fromLonLat([ -74.0446426, 40.6892534 ]);
            var view = new ol.View({
                center: mapCenter,
                zoom: 10
            });

            var map = new ol.Map({
                target: 'map',
                layers: [
                    new ol.layer.Tile({
                        source: new ol.source.OSM()
                    })
                ],
                view: view
            });

            // Array of Icon features
            var iconFeatures = [];
            for (var i = 0; i < locations.length; i++) {

                if(!locations[i].longitude && !locations[i].latitude) {
                    continue;
                }

                var iconFeature = new ol.Feature({
                    type: 'click',
                    name: locations[i].name + ', ' + locations[i].country.name,
                    geometry: new ol.geom.Point(ol.proj.transform([locations[i].longitude, locations[i].latitude], 'EPSG:4326', 'EPSG:3857')),
                });

                iconFeatures.push(iconFeature);
            }

            var vectorSource = new ol.source.Vector({
                features: iconFeatures
            });

            var iconStyle = new ol.style.Style({
                image: new ol.style.Icon(({
                    anchor: [0.5, 1],
                    src: "http://cdn.mapmarker.io/api/v1/pin?size=50&hoffset=1"
                }))
            });

            var vectorLayer = new ol.layer.Vector({
                source: vectorSource,
                style: iconStyle,
                updateWhileAnimating: true,
                updateWhileInteracting: true,
            });
            map.addLayer(vectorLayer);

            var container = document.getElementById('popup');
            var content = document.getElementById('popup-content');
            var closer = document.getElementById('popup-closer');

            var overlay = new ol.Overlay({
                element: container,
                autoPan: true,
                autoPanAnimation: {
                    duration: 250
                }
            });
            map.addOverlay(overlay);

            closer.onclick = function() {
                overlay.setPosition(undefined);
                closer.blur();
                return false;
            };

            map.on('singleclick', function(evt) {

                // Hide existing popup and reset it's offset
                // popup.hide();
                // popup.setOffset([0, 0]);

                // Attempt to find a feature in one of the visible vector layers
                var feature = map.forEachFeatureAtPixel(evt.pixel, function(feature, layer) {
                    return feature;
                });

                if (feature) {
                    var coord = feature.getGeometry().getCoordinates();
                    var props = feature.getProperties();
                    content.innerHTML =
                        // '<a style="color:black; font-weight:600; font-size:11px" href="http://www.somedomain.com/' + props.url + '">' +
                        // '<img width="200" src="' +  props.image + '"  />' +
                        '<div style="width:220px; margin-top:3px">' + props.name + '</div></a>';

                    // Offset the popup so it points at the middle of the marker not the tip
                    overlay.setPosition(evt.coordinate);
                    container.style.display = 'block';
                } else {
                    overlay.setPosition(undefined);
                    closer.blur();
                    container.style.display = 'none';
                }
            });

            return map;
        }

        var map = createMap(cities.data);
    </script>
@endsection
