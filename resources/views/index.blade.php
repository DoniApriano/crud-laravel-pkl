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

</head>

<body>
    <div class="container">
        <h1 class="text-center m-3">Crud Laravel</h1>
        <hr>
        <div class="row">
            <div class="col-md-4">
                <form action="{{ route('products.store') }}" method="post" enctype="multipart/form-data">
                    @csrf

                    <div class="mb-3">
                        <label for="formFile" class="form-label">Gambar Produk</label>
                        <input class="form-control" name="image" type="file" id="formFile">
                        @error('image')
                            <div class="alert alert-danger mt-2">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="name" class="form-label">Nama Produk</label>
                        <input type="text" name="name" class="form-control" id="name"
                            placeholder="Nama Produk">
                        @error('name')
                            <div class="alert alert-danger mt-2">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="stock" class="form-label">Stok Produk</label>
                        <input type="text" name="stock" class="form-control" id="stock"
                            placeholder="Stok Produk">
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
            <div class="col-md-8">
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th scope="col">Gambar</th>
                                <th scope="col">Nama</th>
                                <th scope="col">Jumlah</th>
                                <th scope="col">Kategori</th>
                                <th scope="col">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($products as $p)
                                <tr>
                                    <td><img src="{{ asset('storage/products/' . $p['image']) }}" alt=""
                                            srcset=""></td>
                                    <td>{{ $p['name'] }}</td>
                                    <td>{{ $p['stock'] }}</td>
                                    <td>{{ $p['category']['name'] }}</td>
                                    <td class="text-center">
                                        <form onsubmit="return confirm('Yakin?')"
                                            action="{{ route('products.destroy', $p->id) }}" method="post">
                                            <a href="{{ route('products.edit', $p->id) }}"
                                                class="btn btn-success">Edit</a>
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger">Delete</button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <div class="bg-danger text-center rounded-3">
                                    <h3 class="text-white">Tidak ada produk</h3>
                                </div>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <script src="{{ asset('js/bootstrap.min.js') }}"></script>
    <script>
        $(document).ready(function() {
            $('#categoryDropdown').select2({
                placeholder: 'Pilih Kategori',
                theme: 'form-select',
                ajax: {
                    url: '/getAllCategories',
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

</body>

</html>
