<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::get();
        $categories = Category::get();
        return view('index', compact(['products', 'categories']));
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'image'     => 'required|image|mimes:jpg,png,jpeg,gif,svg|max:2048',
            'name'     => 'required|min:5',
            'stock'   => 'required|numeric',
            'category_id'   => 'required'
        ]);

        $image = $request->image;
        $image->storeAs('/public/products/', $image->hashName());

        Product::create([
            'image'     => $image->hashName(),
            'name'     => $request->name,
            'stock'   => $request->stock,
            'category_id'   => $request->category_id,
        ]);

        return redirect()->route('products.index')->with('success','Berhasil tambah produk');
    }

    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'image'     => 'image|image|mimes:jpeg,png,jpg|max:2048',
            'name'     => 'required|min:5',
            'stock'   => 'required|numeric'
        ]);

        $post = Product::findOrFail($id);

        if ($request->hasFile('image')) {
            $image = $request->image;
            $image->storeAs('/public/products/' . $image->hashName());

            Storage::delete('/public/products/' . $post->image);

            $post->update([
                'image'     => $image->hashName(),
                'name'     => $request->name,
                'stock'   => $request->stock,
            ]);
        } else {
            $post->update([
                'name'     => $request->name,
                'stock'   => $request->stock,
            ]);
        }

        return redirect()->route('products.index')->with('success','Berhasil update data');
    }

    public function edit($id)
    {
        $product = Product::findOrFail($id);
        $categories = Category::get();
        return view('edit', compact(['product', 'categories']));
    }

    public function destroy($id)
    {
        $product = Product::findOrFail($id);

        if (Storage::exists('public/products/' . $product->image)) {
            Storage::delete('public/products/' . $product->image);
        }

        $product->delete();

        return redirect()->route('products.index');
    }
}
