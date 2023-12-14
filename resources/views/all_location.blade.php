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
    <div class="container mt-5">
        <div id="map" style="height: 600px;"></div>
    </div>

    <script src="{{ asset('leaflet/leaflet.js') }}"></script>
    <script>
        var mymap = L.map('map').setView([-7.554749321826491, 110.80869023600576], 20);
        var marker;

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '© OpenStreetMap contributors'
        }).addTo(mymap);

        @foreach ($locations as $location)
            var marker{{ $location->id }} = L.marker([{{ $location->latitude }}, {{ $location->longitude }}]).addTo(
                mymap);
            marker{{ $location->id }}.on('click', function() {
                openLocationMap({{ $location->latitude }}, {{ $location->longitude }});
            });
        @endforeach

        function openLocationMap(latitude, longitude) {
            var locationMap = L.map('map').setView([latitude, longitude], 20);
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '© OpenStreetMap contributors'
            }).addTo(locationMap);
            L.marker([latitude, longitude]).addTo(locationMap);
        }
    </script>
</body>

</html>
