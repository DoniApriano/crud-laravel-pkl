<div class="modal fade" id="modal_input" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false" role="dialog"
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
                <div class="mb-3">
                    <label for="formFile" class="form-label">Gambar Produk</label>
                    <input class="form-control" value="{{ old('image') }}" name="image" id="image" type="file"
                        accept="image/*">
                    <div class="alert alert-danger mt-2 d-none" role="alert" id="alert-image"></div>
                </div>

                <div class="mb-3">
                    <label for="name" class="form-label">Nama Produk</label>
                    <input id="name" type="text" value="{{ old('name') }}" name="name" class="form-control"
                        id="name" placeholder="Nama Produk">
                    <div class="alert alert-danger mt-2 d-none" role="alert" id="alert-name"></div>
                </div>

                <div class="mb-3">
                    <label for="stock" class="form-label">Stok Produk</label>
                    <input id="stock" type="text" value="{{ old('stock') }}" name="stock" class="form-control"
                        pattern="[0-9]+" title="Harus berupa angka" id="stock" placeholder="Stok Produk"
                        oninput="this.value = this.value.replace(/[^0-9]/g, '');">
                    <div class="alert alert-danger mt-2 d-none" role="alert" id="alert-stock"></div>
                </div>

                <div class="mb-3">
                    <label for="category_id" class="form-label">Kategori</label>
                    <select name="category_id" class="form-select" id="category_id" style="width: 100%; z-index: 1050;">
                        <option value="" selected>Pilih Kategori</option>
                        @foreach ($categories as $category)
                            <option value="{{ $category->id }}"
                                {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                    <div class="alert alert-danger mt-2 d-none" role="alert" id="alert-category"></div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-success" id="btn_store">Save</button>
            </div>
        </div>
    </div>
</div>

<script>
    // Memasukkan data
    $('#btn_store').click(function(e) {
        e.preventDefault();

        // Mendapatkan nilai dari input file (gambar)
        let imageInput = $('#image')[0].files[0];

        // Membuat objek FormData
        let formData = new FormData();
        formData.append('image', imageInput);
        formData.append('name', $('#name').val());
        formData.append('stock', $('#stock').val());
        formData.append('category_id', $('#category_id').val());
        let csrfToken = $("meta[name='csrf-token']").attr("content");

        $.ajax({
            url: `{{ route('products.store') }}`,
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

                console.log(response);

                // Menghitung jumlah baris yang sudah ada
                let rowCount = $('#tbl_products tbody tr').length;

                // Menambahkan 1 untuk mendapatkan nomor urut yang baru
                let rowNumber = rowCount - 0 + 1;

                // Membuat baris HTML baru dengan nomor urut
                let product = `
                            <tr id="index_${response.data.id}">
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

                //append to table
                $('#tbl_products').prepend(product);
                // reload tabel
                $('#tbl_list').DataTable().ajax.reload();
                $('#tbl_products tbody tr').each(function(index) {
                    $(this).find('td:first').text(index + 1);
                });

                // Tutup
                $('#modal_input').modal('hide');
            },

            error: function(error) {
                if (error.responseJSON.image) {

                    //show alert
                    $('#alert-image').removeClass('d-none');
                    $('#alert-image').addClass('d-block');

                    //add message to alert
                    $('#alert-image').html(error.responseJSON.image);
                }
                if (error.responseJSON.name) {

                    //show alert
                    $('#alert-name').removeClass('d-none');
                    $('#alert-name').addClass('d-block');

                    //add message to alert
                    $('#alert-name').html(error.responseJSON.name);
                }
                if (error.responseJSON.stock) {

                    //show alert
                    $('#alert-stock').removeClass('d-none');
                    $('#alert-stock').addClass('d-block');

                    //add message to alert
                    $('#alert-stock').html(error.responseJSON.stock);
                }
                if (error.responseJSON.category_id) {

                    //show alert
                    $('#alert-category').removeClass('d-none');
                    $('#alert-category').addClass('d-block');

                    //add message to alert
                    $('#alert-category').html("The category field is required.");
                }
            }
        });
    });
</script>
