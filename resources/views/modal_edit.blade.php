<div class="modal fade" id="modal_edit" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false" role="dialog"
    aria-labelledby="modalTitleId" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered modal-md" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalTitleId">
                    Modal title
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="text-center">
                    <img id="product_image_preview" src="" alt="Product Image" width="150">
                </div>
                <input type="hidden" id="product_id">
                <div class="mb-3">
                    <label for="formFile" class="form-label">Gambar Produk</label>
                    <input class="form-control" value="{{ old('image_edit') }}" name="image_edit" id="image_edit"
                        type="file" accept="image/*">
                    <div class="alert alert-danger mt-2 d-none" role="alert" id="alert-image-edit"></div>
                </div>

                <div class="mb-3">
                    <label for="name" class="form-label">Nama Produk</label>
                    <input id="name_edit" type="text" value="{{ old('name_edit') }}" name="name_edit"
                        class="form-control" placeholder="Nama Produk">
                    <div class="alert alert-danger mt-2 d-none" role="alert" id="alert-name-edit"></div>
                </div>

                <div class="mb-3">
                    <label for="stock" class="form-label">Stok Produk</label>
                    <input id="stock_edit" type="text" value="{{ old('stock_edit') }}" name="stock_edit"
                        class="form-control" pattern="[0-9]+" title="Harus berupa angka" placeholder="Stok Produk"
                        oninput="this.value = this.value.replace(/[^0-9]/g, '');">
                    <div class="alert alert-danger mt-2 d-none" role="alert" id="alert-stock-edit"></div>
                </div>

                <div class="mb-3">
                    <label for="category_id" class="form-label">Kategori</label>
                    <select name="category_id_edit" class="form-select" id="category_id_edit"
                        style="width: 100%; z-index: 1050;">
                    </select>
                    <div class="alert alert-danger mt-2 d-none" role="alert" id="alert-category-edit"></div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-success" id="btn_update">Save</button>
            </div>
        </div>
    </div>
</div>

<script>
    $('#btn_update').click(function(e) {
        e.preventDefault();

        // Mendapatkan nilai dari input file (gambar)
        let imageInput = $('#image_edit')[0].files[0];

        // Id Product
        let product_id = $('#product_id').val();

        // Membuat objek FormData
        let formData = new FormData();
        if (imageInput) {
            formData.append('image_edit', imageInput);
        } else {
            formData.append('image_edit', $('#product_image_preview').attr('src'));
        }
        formData.append('image_edit', imageInput);
        formData.append('name_edit', $('#name_edit').val());
        formData.append('stock_edit', $('#stock_edit').val());
        formData.append('category_id_edit', $('#category_id_edit').val());
        let csrfToken = $("meta[name='csrf-token']").attr("content");

        console.log(formData);

        $.ajax({
            url: `/products/${product_id}`,
            type: "POST",
            data: formData,
            contentType: false,
            processData: false,
            headers: {
                'X-CSRF-TOKEN': csrfToken
            },

            success: function(response) {
                Swal.fire({
                    type: 'success',
                    icon: 'success',
                    title: `${response.message}`,
                    showConfirmButton: false,
                    timer: 3000
                });

                console.log('formData:', formData);
                console.log(response);

                let rowCount = $('#tbl_products tbody tr').length;

                // Menambahkan 1 untuk mendapatkan nomor urut yang baru
                let rowNumber = rowCount + 1;

                // Membuat baris HTML baru dengan nomor urut
                let product = `
                            <tr id="index_${response.data.id}" data-id="${response.data.id}">
                                <td>${rowNumber}</td>
                                <td><img src="/storage/products/${response.data.image}" alt="Product Image" width="100"></td>
                                <td>${response.data.name}</td>
                                <td>${response.data.stock}</td>
                                <td>${response.data.category.name}</td>
                                <td >
                                    <a href="javascript:void(0)" id="btn_edit_product" data-id="${response.data.id}" class="btn btn-primary btn-sm">EDIT</a>
                                    <a href="javascript:void(0)" id="btn_delete_product" data-id="${response.data.id}" class="btn btn-danger btn-sm">DELETE</a>
                                </td>
                            </tr>
                        `;

                // Cari rownya di tabel
                let existingRow = $(`#tbl_products tbody tr[data-id="${response.data.id}"]`);

                if (existingRow.length) {
                    // repleace
                    existingRow.replaceWith(product);
                } else {
                    $('#tbl_products tbody').append(product);
                }

                // Close modal
                $('#modal_edit').modal('hide');

                // reload tabel
                $('#tbl_list').DataTable().ajax.reload();
            },

            error: function(error) {
                console.log(error.responseJSON);
                if (error.responseJSON.image_edit) {
                    //show alert
                    $('#alert-image-edit').removeClass('d-none');
                    $('#alert-image-edit').addClass('d-block');

                    //add message to alert
                    $('#alert-image-edit').html(error.responseJSON.image_edit);
                }
                if (error.responseJSON.name_edit) {

                    //show alert
                    $('#alert-name-edit').removeClass('d-none');
                    $('#alert-name-edit').addClass('d-block');

                    //add message to alert
                    $('#alert-name-edit').html(error.responseJSON.name_edit);
                }
                if (error.responseJSON.stock_edit) {

                    //show alert
                    $('#alert-stock-edit').removeClass('d-none');
                    $('#alert-stock-edit').addClass('d-block');

                    //add message to alert
                    $('#alert-stock-edit').html(error.responseJSON.stock_edit);
                }
                if (error.responseJSON.category_id_edit) {

                    //show alert
                    $('#alert-category-edit').removeClass('d-none');
                    $('#alert-category-edit').addClass('d-block');

                    //add message to alert
                    $('#alert-category-edit').html("The category field is required.");
                }
            }
        });
    });
</script>
