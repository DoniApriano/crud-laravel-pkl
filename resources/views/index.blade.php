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

    <script src="https://cdn.datatables.net/1.10.16/js/jquery.dataTables.min.js"></script>
    <link href="https://cdn.datatables.net/1.10.16/css/jquery.dataTables.min.css" rel="stylesheet">
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
                <table id="tbl_list" class="table table-striped table-bordered" cellspacing="0" width="100%">
                    <thead>
                        <tr>
                            <th>Gambar</th>
                            <th>Nama</th>
                            <th>Jumlah</th>
                            <th>Kategori</th>
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


    <script type="text/javascript">
        $(document).ready(function() {
            $('#tbl_list').DataTable({
                processing: true,
                serverSide: true,
                ajax: '{{ route('products.index') }}',
                columns: [{
                        data: 'image',
                        name: 'image',
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
                ]
            });
        });
    </script>


</body>

</html>
