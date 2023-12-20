<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>CRUD</title>
    <link rel="stylesheet" href="{{ asset('css/bootstrap.min.css') }}">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.12.1/css/dataTables.bootstrap5.min.css">

    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <script src="https://cdn.datatables.net/1.10.16/js/jquery.dataTables.min.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body>
    <div class="container">
        <h1 class="text-center m-3">Crud Laravel</h1>
        <hr>
        <div class="row justify-content-center">
            <div class=" shadow p-3 rounded-3 mb-3">
                <a href="javascript:void(0)" class="btn btn-success mb-2" id="btn-open-modal-input">TAMBAH</a>
                @include('modal_input')
            </div>
            <div class=" shadow p-3 rounded-3">
                <table id="tbl_list" class="table table-bordered table-hover" cellspacing="0" width="100%">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Gambar</th>
                            <th>Nama</th>
                            <th>Jumlah</th>
                            <th>Kategori</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody id="tbl_products">
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    @include('modal_edit')
    @include('delete_product')

    <script>
        // Memunculkan Modal input
        $('body').on('click', '#btn-open-modal-input', function() {
            //open modal
            $('#modal_input').modal('show');
        });

        $('body').on('click', '#btn_edit_product', function() {
            //id
            let product_id = $(this).data('id');
            console.log(product_id);

            $.ajax({
                url: `/products/${product_id}`,
                type: "GET",
                cache: false,
                success: function(response) {
                    console.log(response);
                    //fill data to form
                    $('#product_id').val(response.data.id);
                    $('#name_edit').val(response.data.name);
                    $('#stock_edit').val(response.data.stock);
                    $('#gambar').val(response.data.image);
                    $('#product_image_preview').attr('src', `/storage/products/${response.data.image}`);
                    $('#category_id_edit').empty();

                    // Menambahkan Default option pada select category
                    $('#category_id_edit').append('<option value="" selected>Pilih Kategori</option>');

                    // Menambahkan option-option pada category
                    response.categories.forEach(category => {
                        let isSelected = category.id == response.data.category_id ? 'selected' :
                            '';
                        let option =
                            `<option value="${category.id}" ${isSelected}>${category.name}</option>`;
                        $('#category_id_edit').append(option);
                    });

                    //open modal
                    $('#modal_edit').modal('show');
                }
            });
        });

        /**
         * Dropdown search tapi belum bisa kalau pakai modal
         */
        // $('#categoryDropdown').select2({
        //     placeholder: 'Pilih Kategori',
        //     ajax: {
        //         url: '{{ route('getAllCategories') }}',
        //         dataType: 'json',
        //         delay: 250,
        //         processResults: function(data) {
        //             return {
        //                 results: data
        //             };
        //         },
        //         cache: true
        //     }
        // });

        // DataTable
        $(document).ready(function() {
            $('#tbl_list').DataTable({
                processing: true,
                serverSide: true,
                ajax: '{{ route('products.index') }}',
                createdRow: function(row, data, dataIndex) {
                    // Menambahkan ID ke elemen <tr>
                    $(row).attr('id', 'index_' + data.id);
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
                        data: 'image',
                        name: 'image',
                        orderable: false,
                        searchable: false,
                        render: function(data, type, full, meta) {
                            return '<img src="' + `/storage/products/${data}` +
                                '" alt="Product Image" width="100">';
                        }
                    },
                    {
                        data: 'name',
                        name: 'name'
                    },
                    {
                        data: 'stock',
                        name: 'stock'
                    },
                    {
                        data: 'category_name',
                        name: 'category_name'
                    },
                    {
                        data: null,
                        orderable: false,
                        searchable: false,
                        render: function(data, type, full, meta) {
                            return '<a href="javascript:void(0)" id="btn_edit_product" data-id="' +
                                full.id + '" class="btn btn-primary btn-sm">EDIT</a> ' +
                                '<a href="javascript:void(0)" id="btn_delete_product" data-id="' +
                                full
                                .id + '" class="btn btn-danger btn-sm">DELETE</a>';
                        }
                    }

                ]
            });

            // Menangani peristiwa klik pada tombol "Ubah"
            $('#tbl_list').on('click', '.btn-edit', function() {
                // Logika untuk menangani peristiwa "Ubah" (jika diperlukan)
                console.log('Ubah produk dengan ID:', $(this).data('id'));
            });

            // Menangani peristiwa klik pada tombol "Hapus"
            $('#tbl_list').on('submit', '.delete-form', function() {
                // Logika untuk menangani peristiwa "Hapus" (jika diperlukan)
                console.log('Hapus produk dengan ID:', $(this).data('id'));
            });
        });

        function confirmDelete(event) {
            event.preventDefault();
            Swal.fire({
                title: 'Yakin?',
                text: 'Anda tidak dapat mengembalikan ini!',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Ya, hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    event.target.submit();
                }
            });
        }
    </script>

    <script src="https://cdn.datatables.net/1.12.1/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.12.1/js/dataTables.bootstrap5.min.js"></script>
    <script src="{{ asset('js/bootstrap.min.js') }}"></script>

</body>

</html>
