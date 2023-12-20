<div class="modal fade" id="modal_edit" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false" role="dialog"
    aria-labelledby="modalTitleId" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalTitleId">
                    Modal title
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div id="map_edit" style="height: 400px;"></div>
                <form id="location_form_edit" method="post" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" id="location_id" name="location_id">
                    <div class="mb-3">
                        <label for="" class="form-label">Latitude</label>
                        <input class="form-control" name="latitude_edit" type="text" id="latitude_edit" required>
                        <div class="alert alert-danger mt-2 d-none" role="alert" id="alert-latitude-edit"></div>
                    </div>
                    <div class="mb-3">
                        <label for="" class="form-label">Longitude</label>
                        <input class="form-control" name="longitude_edit" type="text" id="longitude_edit" required>
                        <div class="alert alert-danger mt-2 d-none" role="alert" id="alert-longitude-edit"></div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <div class="mb-3">
                    <button type="button" class="btn btn-success" id="editBtn">Simpan</button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    $('#editBtn').on('click', function(e) {
        e.preventDefault();

        var formData = $('#location_form_edit').serialize();
        let location_id = $('#location_id').val();

        $.ajax({
            url: '/location/' + location_id,
            type: 'POST',
            data: formData,
            success: function(response) {
                console.log(response);

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
                console.log(formData);
                console.log(error.responseJSON);
                if (error.responseJSON.latitude) {
                    $('#alert-latitude-edit').removeClass('d-none');
                    $('#alert-latitude-edit').addClass('d-block');
                    $('#alert-latitude-edit').html(error.responseJSON.latitude);
                }

                if (error.responseJSON.longitude) {
                    $('#alert-longitude-edit').removeClass('d-none');
                    $('#alert-longitude-edit').addClass('d-block');
                    $('#alert-longitude-edit').html(error.responseJSON.longitude);
                }
            }
        });
    });
</script>
