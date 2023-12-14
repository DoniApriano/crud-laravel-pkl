<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>CRUD</title>
    <link rel="stylesheet" href="{{ asset('css/bootstrap.min.css') }}">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.12.1/css/dataTables.bootstrap5.min.css">

    <script src="https://cdn.datatables.net/1.10.16/js/jquery.dataTables.min.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body>
    <div class="container">
        <h1 class="text-center m-3">Crud Laravel</h1>
        <hr>
        <div class="row justify-content-center">
            <div class=" shadow p-3 rounded-3 mb-3">
                <form action="{{ route('products.store') }}" method="post" enctype="multipart/form-data">
                    @csrf

                    <div class="mb-3">
                        <label for="formFile" class="form-label">Gambar Produk</label>
                        <input class="form-control" value="{{ old('image') }}" name="image" type="file"
                            accept="image/*" id="formFile">
                        @error('image')
                            <div class="alert alert-danger mt-2">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="name" class="form-label">Nama Produk</label>
                        <input type="text" value="{{ old('name') }}" name="name" class="form-control"
                            id="name" placeholder="Nama Produk">
                        @error('name')
                            <div class="alert alert-danger mt-2">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="stock" class="form-label">Stok Produk</label>
                        <input type="text" value="{{ old('stock') }}" name="stock" class="form-control"
                            pattern="[0-9]+" title="Harus berupa angka" id="stock" placeholder="Stok Produk"
                            oninput="this.value = this.value.replace(/[^0-9]/g, '');">
                        @error('stock')
                            <div class="alert alert-danger mt-2">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="categoryDropdown" class="form-label">Kategori</label>
                        <select name="category_id" class="form-select" id="categoryDropdown">
                            <option value="" selected>Pilih Kategori</option>
                            @foreach ($categories as $category)
                                <option value="{{ $category->id }}"
                                    {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('category_id')
                            <div class="alert alert-danger mt-2">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <button type="submit" class="btn btn-success">Simpan</button>
                    </div>
                </form>

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
                    <tbody>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <script src="{{ asset('js/bootstrap.min.js') }}"></script>
    <script>
        $(document).ready(function() {
            $('#categoryDropdown').select2({
                placeholder: 'Pilih Kategori',
                ajax: {
                    url: '{{ route('getAllCategories') }}',
                    dataType: 'json',
                    delay: 250,
                    processResults: function(data) {
                        return {
                            results: data
                        };
                    },
                    cache: true
                }
            });
        });
    </script>


    <script>
        $(document).ready(function() {
            $('#tbl_list').DataTable({
                processing: true,
                serverSide: true,
                ajax: '{{ route('products.index') }}',
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
                            var editUrl = '{{ route('products.edit', ':id') }}'.replace(':id', full
                                .id);
                            var deleteUrl = '{{ route('products.destroy', ':id') }}'.replace(':id',
                                full.id);

                            return '<div class="row">' +
                                '<div class="col-md-3">' +
                                '<a href="' + editUrl +
                                '" class="btn btn-success btn-edit m-1">Ubah</a>' +
                                '</div>' +
                                '<div class="col-md-3">' +
                                '<form class="delete-form" onsubmit="confirmDelete(event)"' +
                                ' action="' + deleteUrl + '" method="post">' +
                                '@csrf' +
                                '@method('DELETE')' +
                                '<button type="submit" class="btn btn-danger m-1 btn-delete">Hapus</button>' +
                                '</form>' +
                                '</div>' +
                                '</div>';

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

</body>

</html>
