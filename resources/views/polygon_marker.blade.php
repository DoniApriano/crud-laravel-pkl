<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>CRUD</title>
    <link rel="stylesheet" href="{{ asset('css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('leaflet/leaflet.css') }}" />
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

        <div class="mb-3">
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
                                    <div id="map_{{ $location->id }}" class="minimap" style="height: 100px;"></div>
                                    <script>
                                        var map_{{ $location->id }} = L.map('map_{{ $location->id }}').setView([{{ $location->latitude }},
                                            {{ $location->longitude }}
                                        ], 20);
                                        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                                            attribution: '© OpenStreetMap contributors'
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

    <script src="{{ asset('leaflet/leaflet.js') }}"></script>
    <script>
        var mymap = L.map('map').setView([-7.554749321826491, 110.80869023600576], 20);
        var marker;

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '© OpenStreetMap contributors'
        }).addTo(mymap);

        mymap.on('click', function(e) {
            if (marker) {
                mymap.removeLayer(marker);
            }
            marker = new L.marker(e.latlng).addTo(mymap);

            // Update nilai input formulir tersembunyi
            document.getElementById('latitude').value = e.latlng.lat;
            document.getElementById('longitude').value = e.latlng.lng;
            console.log(e.latlng.lat);
            console.log(e);
        });
    </script>
</body>

</html>
