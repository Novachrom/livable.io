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
    function createMap(locations, selectedCountry) {
        var mapCenter = ol.proj.fromLonLat(getAveragePoint(locations));
        var map = createCountriesMap(mapCenter, selectedCountry);

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

            // Attempt to find a city feature in one of the visible vector layers
            var feature = map.forEachFeatureAtPixel(evt.pixel, function(feature, layer) {
                if(feature.get('isCity')) {
                    return feature;
                }
            });
            if(!feature) {
                return;
            }

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

    function createCountriesMap(center, selectedCountry) {
        console.log(selectedCountry);
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

        var vectorSource = new ol.source.Vector({
            url: '{{url('data/countries.geojson?v=2')}}',
            format: new ol.format.GeoJSON()
        });


        var vectorLayer = new ol.layer.Vector({
            source: vectorSource,
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

        // map.on('pointermove', function(evt) {
        //     if (evt.dragging) {
        //         return;
        //     }
        //     var pixel = map.getEventPixel(evt.originalEvent);
        //     displayFeatureInfo(pixel);
        // });
        //
        // map.on('click', function(evt) {
        //     displayFeatureInfo(evt.pixel);
        // });

        map.on('postrender', () => {
            let features = vectorSource.getFeatures();
            if(features.length === 0) return;

            if(featureOverlay.getSource().getFeatures().length !== 0) {
                console.log('feature already set ');
                return;
            }

            for (feature of features) {
                console.log()
                if(feature.get('name') === selectedCountry.name) {
                    console.log('feature set: ', feature.get('name'));
                    featureOverlay.getSource().addFeature(feature);
                    return;
                }
            }
        });

        return map;
    }

    var country = {!! $country->toJson() !!};
    var map = createMap(cities.data, country);
    document.getElementById('pills-map-tab').addEventListener('click', function () {
        console.log('event');
        setTimeout(() => map.updateSize(), 200);
    });
</script>

