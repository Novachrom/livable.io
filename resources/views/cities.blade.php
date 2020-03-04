
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
    <span id="info"></span>
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

        function getAveragePoint(cities) {
            let X = 0.0;
            let Y = 0.0;
            let Z = 0.0;

            for (city of cities) {
                let lat = city.latitude * Math.PI / 180;
                let lon = city.longitude * Math.PI / 180;

                let a = Math.cos(lat) * Math.cos(lon);
                let b = Math.cos(lat) * Math.sin(lon);
                let c = Math.sin(lat);

                X += a;
                Y += b;
                Z += c;
            }

            X /= cities.length;
            Y /= cities.length;
            Z /= cities.length;

            let latitude = Math.atan2(Y, X);
            let hyp = Math.sqrt(X * X + Y * Y);
            let longitude = Math.atan2(Z, hyp);

            return [(latitude * 180 / Math.PI), (longitude * 180 / Math.PI)];
        }

        var cities = {!!  json_encode($cities) !!};
        // console.log(cities);
        function createMap(locations) {

            var mapCenter = ol.proj.fromLonLat(getAveragePoint(locations));
            var map = createCountriesMap(mapCenter);

            // Array of Icon features
            var iconFeatures = [];
            for (var i = 0; i < locations.length; i++) {

                if(!locations[i].longitude && !locations[i].latitude) {
                    continue;
                }

                var iconFeature = new ol.Feature({
                    type: 'click',
                    name: locations[i].name + ', ' + locations[i].country.name,
                    isCity: true,
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
                // autoPan: true,
                // autoPanAnimation: {
                //     duration: 250
                // }
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

                if (feature && feature.getProperties().isCity) {
                    var coord = feature.getGeometry().getCoordinates();
                    var props = feature.getProperties();
                    content.innerHTML =
                        // '<a style="color:black; font-weight:600; font-size:11px" href="http://www.somedomain.com/' + props.url + '">' +
                        // '<img width="200" src="' +  props.image + '"  />' +
                        '<div style="width:220px; margin-top:3px">' + props.name + '</div></a>';

                    // Offset the popup so it points at the middle of the marker not the tip
                    overlay.setPosition(coord);
                    container.style.display = 'block';
                } else {
                    overlay.setPosition(undefined);
                    closer.blur();
                    container.style.display = 'none';
                }
            });

            return map;
        }

        function createCountriesMap(center) {
            var style = new ol.style.Style({
                fill: new ol.style.Fill({
                    color: 'rgba(255, 255, 255, 0.6)'
                }),
                stroke: new ol.style.Stroke({
                    color: '#319FD3',
                    width: 1
                }),
                text: new ol.style.Text({
                    font: '12px Calibri,sans-serif',
                    fill: new ol.style.Fill({
                        color: '#000'
                    }),
                    stroke: new ol.style.Stroke({
                        color: '#fff',
                        width: 3
                    })
                })
            });

            var vectorLayer = new ol.layer.Vector({
                source: new ol.source.Vector({
                    url: '{{url('data/countries.geojson')}}',
                    format: new ol.format.GeoJSON()
                }),
                style: function(feature) {
                    style.getText().setText(feature.get('name'));
                    return style;
                }
            });

            var map = new ol.Map({
                layers: [
                    // new ol.layer.Tile({
                    //     source: new ol.source.OSM()
                    // }),
                    vectorLayer,
                ],
                target: 'map',
                view: new ol.View({
                    center: center,
                    zoom: 4
                })
            });


            var highlightStyle = new ol.style.Style({
                stroke: new ol.style.Stroke({
                    color: '#f00',
                    width: 1
                }),
                fill: new ol.style.Fill({
                    color: 'rgba(255,0,0,0.1)'
                }),
                text: new ol.style.Text({
                    font: '12px Calibri,sans-serif',
                    fill: new ol.style.Fill({
                        color: '#000'
                    }),
                    stroke: new ol.style.Stroke({
                        color: '#f00',
                        width: 3
                    })
                })
            });

            var featureOverlay = new ol.layer.Vector({
                source: new ol.source.Vector(),
                map: map,
                style: function(feature) {
                    highlightStyle.getText().setText(feature.get('name'));
                    return highlightStyle;
                }
            });

            var highlight;
            var displayFeatureInfo = function(pixel) {

                vectorLayer.getFeatures(pixel).then(function(features) {
                    var feature = features.length ? features[0] : undefined;
                    var info = document.getElementById('info');
                    if (features.length) {
                        info.innerHTML = feature.getId() + ': ' + feature.get('name');
                    } else {
                        info.innerHTML = '&nbsp;';
                    }

                    if (feature !== highlight) {
                        if (highlight) {
                            featureOverlay.getSource().removeFeature(highlight);
                        }
                        if (feature) {
                            featureOverlay.getSource().addFeature(feature);
                        }
                        highlight = feature;
                    }
                });

            };


            // map.on('pointermove', function(evt) {
            //     if (evt.dragging) {
            //         return;
            //     }
            //     var pixel = map.getEventPixel(evt.originalEvent);
            //     displayFeatureInfo(pixel);
            // });

            // map.on('click', function(evt) {
            //     displayFeatureInfo(evt.pixel);
            // });

            return map;
        }

        var map = createMap(cities.data);
        // let map = createCountriesMap();
    </script>
@endsection
