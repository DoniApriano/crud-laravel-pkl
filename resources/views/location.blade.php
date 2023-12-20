<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>CRUD</title>
    <link rel="stylesheet" href="{{ asset('css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('leaflet/leaflet.css') }}" />
    <script src="{{ asset('leaflet/leaflet.js') }}"></script>
    <link rel="stylesheet" href="https://unpkg.com/leaflet-draw/dist/leaflet.draw.css" />
    <script src="https://unpkg.com/leaflet-draw"></script>

    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.12.1/css/dataTables.bootstrap5.min.css">
    <script src="https://cdn.datatables.net/1.10.16/js/jquery.dataTables.min.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body>
    <div class="container">
        <form id="locationForm" method="post" enctype="multipart/form-data">
            @csrf
            <div class="mb-3">
                <label for="" class="form-label">Latitude</label>
                <input class="form-control" name="latitude" type="text" id="latitude" required>
                <div class="alert alert-danger mt-2 d-none" role="alert" id="alert-latitude"></div>
            </div>
            <div class="mb-3">
                <label for="" class="form-label">Longitude</label>
                <input class="form-control" name="longitude" type="text" id="longitude" required>
                <div class="alert alert-danger mt-2 d-none" role="alert" id="alert-longitude"></div>
            </div>

            <div class="mb-3">
                <button type="button" class="btn btn-success" id="submitBtn">Simpan</button>
            </div>
        </form>

        <div id="map" style="height: 400px;"></div>

        <div class="my-3">
            <h3>Data Lokasi</h3>
            <div class="table-responsive">
                <table id="tbl_locations" class="table table-hover">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Latitude</th>
                            <th>Longitude</th>
                            <th>Maps</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
        </div>


    </div>

    @include('modal_location')
    @include('delete_location')

    <script>
        $('#modal_edit').on('shown.bs.modal', function(e) {
            var locationId = $('#location_form_edit input[name="location_id"]').val();

            $.ajax({
                url: '/location/' + locationId,
                type: 'GET',
                success: function(response) {
                    var latitude = response.data.latitude;
                    var longitude = response.data.longitude;

                    // Update form fields
                    $('#latitude_edit').val(latitude);
                    $('#longitude_edit').val(longitude);

                    // Clear existing markers on the modal map

                    function addMarkerToMap(map, lat, lng) {
                        return L.marker([lat, lng]).addTo(map);
                    }

                    var map_edit = L.map('map_edit').setView([latitude, longitude], 30);
                    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {}).addTo(
                        map_edit);

                    map_edit.eachLayer(function(layer) {
                        if (layer instanceof L.Marker) {
                            map_edit.removeLayer(layer);
                        }
                    });

                    var marker = addMarkerToMap(map_edit, latitude, longitude);

                    var drawnItems = new L.FeatureGroup();
                    map_edit.addLayer(drawnItems);

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

                    map_edit.addControl(drawControl);
                    map_edit.on('draw:created', function(e) {
                        var layer = e.layer;
                        var coords;
                        var array = [];

                        if (layer instanceof L.Polygon || layer instanceof L.Polyline) {
                            coords = layer.getLatLngs()[0];
                            console.log('poly', coords);
                        } else if (layer instanceof L.Marker || layer instanceof L.Circle ||
                            layer instanceof L.Rectangle) {
                            coords = layer.getLatLng();
                            array.push(coords);
                            console.log('oth', coords);
                            console.log('array = ', array);
                            document.getElementById('latitude_edit').value = coords.lat;
                            document.getElementById('longitude_edit').value = coords.lng;

                            // Hapus marker lama jika ada
                            map_edit.eachLayer(function(existingLayer) {
                                if (existingLayer instanceof L.Marker) {
                                    map_edit.removeLayer(existingLayer);
                                }
                            });

                            map_edit.addLayer(layer);

                            // Set Map View dari map edit
                            function setMapView(map, lat, lng) {
                                map.setView([lat, lng], 30);
                            }
                            setMapView(map_edit, coords.lat, coords.lng);
                        }
                    });

                },
                error: function(error) {
                    console.error('Error fetching location data:', error);
                }
            });
        });

        // Modal Edit
        $('body').on('click', '#btn_edit_location', function() {
            //id
            let location_id = $(this).data('id');
            console.log(location_id);

            $.ajax({
                url: `/location/${location_id}`,
                type: "GET",
                cache: false,
                success: function(response) {
                    console.log(response);
                    //fill data to form
                    $('#location_id').val(response.data.id);
                    $('#latitude_edit').val(response.data.latitude);
                    $('#longitude_edit').val(response.data.longitude);

                    //open modal
                    $('#modal_edit').modal('show');
                }
            });
        });

        // Input
        $('#submitBtn').on('click', function() {
            var formData = $('#locationForm').serialize();

            $.ajax({
                url: '{{ route('postLocation') }}',
                type: 'POST',
                data: formData,
                success: function(response) {
                    // Handle success, e.g., show a success message
                    console.log(response);

                    // Clear form fields
                    $('#latitude').val('');
                    $('#longitude').val('');

                    Swal.fire({
                        type: 'success',
                        icon: 'success',
                        title: `${response.message}`,
                        showConfirmButton: false,
                        timer: 3000
                    });

                    // Reload DataTable
                    $('#tbl_locations').DataTable().ajax.reload();
                },
                error: function(error) {
                    if (error.responseJSON.latitude) {
                        $('#alert-latitude').removeClass('d-none');
                        $('#alert-latitude').addClass('d-block');
                        $('#alert-latitude').html(error.responseJSON.latitude);
                    }

                    if (error.responseJSON.longitude) {
                        $('#alert-longitude').removeClass('d-none');
                        $('#alert-longitude').addClass('d-block');
                        $('#alert-longitude').html(error.responseJSON.longitude);
                    }
                }
            });
        });

        // datatable locations
        $(document).ready(function() {
            $('#tbl_locations').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: '{{ route('location') }}',
                    type: 'GET',
                },
                columns: [{
                        data: null,
                        orderable: false,
                        searchable: false,
                        render: function(data, type, full, meta) {
                            return meta.row + 1;
                        }
                    },
                    {
                        data: 'latitude',
                        name: 'latitude'
                    },
                    {
                        data: 'longitude',
                        name: 'longitude'
                    },
                    {
                        data: null,
                        render: function(data, type, row) {
                            return '<div id="map_' + row.id +
                                '" class="minimap" style="height: 200px; width: 200px;"></div>';
                        }
                    },
                    {
                        data: null,
                        orderable: false,
                        searchable: false,
                        render: function(data, type, full, meta) {
                            return '<a href="javascript:void(0)" id="btn_edit_location" data-id="' +
                                full.id + '" class="btn btn-primary btn-sm">EDIT</a> ' +
                                '<a href="javascript:void(0)" id="btn_delete_location" data-id="' +
                                full
                                .id + '" class="btn btn-danger btn-sm">DELETE</a>';
                        }
                    }
                ],
                drawCallback: function(settings) {
                    var api = this.api();
                    api.rows().every(function() {
                        var data = this.data();
                        var mapId = 'map_' + data.id;

                        var rowMap = L.map(mapId).setView([data.latitude, data.longitude], 30);
                        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {})
                            .addTo(rowMap);

                        L.marker([data.latitude, data.longitude]).addTo(rowMap);
                    });
                }
            });
        });

        var mymap = L.map('map').setView([-7.554749321826491, 110.80869023600576], 20);

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {}).addTo(mymap);

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

        $('#submitBtn').on('click', function() {
            drawnItems.clearLayers();
        });

        mymap.addControl(drawControl);

        mymap.on('draw:created', function(e) {
            drawnItems.clearLayers();

            var layer = e.layer;
            var coords;
            var array = []

            if (layer instanceof L.Polygon || layer instanceof L.Polyline) {
                coords = layer.getLatLngs()[0]
                console.log('poly', coords)
            } else if (layer instanceof L.Marker || layer instanceof L.Circle || layer instanceof L.Rectangle) {
                coords = layer.getLatLng()
                array.push(coords)
                console.log('oth', coords)
                console.log('array = ', array)
                document.getElementById('latitude').value = layer.getLatLng().lat;
                document.getElementById('longitude').value = layer.getLatLng().lng;
            }

            drawnItems.addLayer(layer);
        });
    </script>
    <script src="{{ asset('js/bootstrap.min.js') }}"></script>
</body>

</html>
