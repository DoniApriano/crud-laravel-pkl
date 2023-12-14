<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>CRUD</title>
    <link rel="stylesheet" href="{{ asset('css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('leaflet/leaflet.css') }}" />
    <script src="{{ asset('leaflet/leaflet.js') }}"></script>
    <link rel="stylesheet" href="https://unpkg.com/leaflet-draw/dist/leaflet.draw.css" />
    <script src="https://unpkg.com/leaflet-draw"></script>
</head>

<body>
    <div class="container">
        @if (Session::has('success'))
            <div class="alert alert-primary" role="alert">
                A simple primary alert—check it out!
            </div>
        @elseif(Session::has('error'))
            <div class="alert alert-danger" role="alert">
                A simple primary alert—check it out!
            </div>
        @endif

        <form action="{{ route('postLocation') }}" method="post" enctype="multipart/form-data">
            @csrf

            <div class="mb-3">
                <label for="" class="form-label">Latitude</label>
                <input class="form-control" name="latitude" type="text" id="latitude">
                @error('latitude')
                    <div class="alert alert-danger mt-2">
                        {{ $message }}
                    </div>
                @enderror
            </div>
            <div class="mb-3">
                <label for="" class="form-label">Longitude</label>
                <input class="form-control" name="longitude" type="text" id="longitude">
                @error('longitude')
                    <div class="alert alert-danger mt-2">
                        {{ $message }}
                    </div>
                @enderror
            </div>

            <div class="mb-3">
                <button type="submit" class="btn btn-success">Simpan</button>
            </div>
        </form>

        <div id="map" style="height: 400px;"></div>

        <div class="my-3">
            <h3>Data Lokasi</h3>
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Latitude</th>
                            <th>Longitude</th>
                            <th>Maps</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($locations as $location)
                            <tr>
                                <td>{{ $location->latitude }}</td>
                                <td>{{ $location->longitude }}</td>
                                <td>
                                    <div id="map_{{ $location->id }}" class="minimap"
                                        style="height: 200px; width: 200px;"></div>
                                    <script>
                                        var map_{{ $location->id }} = L.map('map_{{ $location->id }}').setView([{{ $location->latitude }},
                                            {{ $location->longitude }}
                                        ], 20);
                                        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                                        }).addTo(map_{{ $location->id }});
                                        L.marker([{{ $location->latitude }}, {{ $location->longitude }}]).addTo(map_{{ $location->id }});
                                    </script>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>


    </div>

    <script>
        var mymap = L.map('map').setView([-7.554749321826491, 110.80869023600576], 20);

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        }).addTo(mymap);

        // Menambahkan kontrol Leaflet Draw
        var drawnItems = new L.FeatureGroup();
        mymap.addLayer(drawnItems);

        var drawControl = new L.Control.Draw({
            draw: {
                polygon: true,
                marker: true,
                circle: true,
                polyline: true,
                rectangle: true
            },
            edit: {
                featureGroup: drawnItems
            }
        });

        mymap.addControl(drawControl);

        mymap.on('draw:created', function(e) {
            drawnItems.clearLayers();

            var layer = e.layer;
            var coords;
            var array = []

            if (layer instanceof L.Polygon || layer instanceof L.Polyline) {
                coords = layer.getLatLngs()[0]
                alert(JSON.stringify(coords))
                console.log('poly',coords)
            } else if (layer instanceof L.Marker || layer instanceof L.Circle || layer instanceof L.Rectangle) {
                coords = layer.getLatLng()
                array.push(coords)
                console.log('oth',coords)
                console.log('array = ',array)
                alert(JSON.stringify(array))
                document.getElementById('latitude').value = layer.getLatLng().lat;
                document.getElementById('longitude').value = layer.getLatLng().lng;
            }

            drawnItems.addLayer(layer);
        });

    </script>
</body>

</html>
