<script>
    $('body').on('click', '#btn_delete_location', function() {

        let location_id = $(this).data('id');
        let token = $("meta[name='csrf-token']").attr("content");

        Swal.fire({
            title: 'Apakah Kamu Yakin?',
            text: "ingin menghapus data ini!",
            icon: 'warning',
            showCancelButton: true,
            cancelButtonText: 'TIDAK',
            confirmButtonText: 'YA, HAPUS!'
        }).then((result) => {
            if (result.isConfirmed) {
                //fetch to delete data
                $.ajax({
                    url: `/location/${location_id}`,
                    type: "DELETE",
                    cache: false,
                    data: {
                        "_token": token
                    },
                    success: function(response) {

                        //show success message
                        Swal.fire({
                            type: 'success',
                            icon: 'success',
                            title: `${response.message}`,
                            showConfirmButton: false,
                            timer: 3000
                        });

                        // //remove post on table
                        // $(`#index_${location_id}`).remove();

                        // reload tabel
                        $('#tbl_locations').DataTable().ajax.reload();
                    },
                    error: function (error) {
                        console.log(error);
                    }
                });
            }
        })

    });
</script>
