<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Validator;

class ProductController extends Controller
{
    public function index()
    {
        // $products = Product::get();
        $categories = Category::get();
        if (request()->ajax()) {
            $products = Product::with('category')->latest()->get();
            return DataTables::of($products)
                ->addColumn('category_name', function ($product) {
                    return $product->category->name;
                })
                ->make(true);
        }
        return view('index', compact(['categories']));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'image'     => 'required|image|mimes:jpg,png,jpeg,gif,svg|max:2048',
            'name'     => 'required|min:5',
            'stock'   => 'required|numeric',
            'category_id'   => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $image = $request->image;
        $image->storeAs('/public/products/', $image->hashName());

        $product = Product::create([
            'image'     => $image->hashName(),
            'name'     => $request->name,
            'stock'   => $request->stock,
            'category_id'   => $request->category_id,
        ]);

        $productWithCategory = Product::with('category')->find($product->id);

        return response()->json([
            'success' => true,
            'message' => 'Data Berhasil Disimpan!',
            'data'    => $productWithCategory
        ]);
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'image_edit' => $request->hasFile('image_edit') ? 'image|mimes:jpg,png,jpeg,gif,svg|max:2048' : '',
            'name_edit'     => 'required|min:5',
            'stock_edit'   => 'required|numeric',
            'category_id_edit'   => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $product = Product::with('category')->findOrFail($id);

        if ($request->hasFile('image_edit')) {
            $image = $request->image_edit;
            $image->storeAs('/public/products/' . $image->hashName());

            Storage::delete('/public/products/' . $product->image);

            $product->update([
                'image'     => $image->hashName(),
                'name'     => $request->name_edit,
                'stock'   => $request->stock_edit,
                'category_id'   => $request->category_id_edit,
            ]);
        } else {
            $product->update([
                'name'     => $request->name_edit,
                'stock'   => $request->stock_edit,
                'category_id'   => $request->category_id_edit,
            ]);
        }

        $product->load('category');

        return response()->json([
            'success' => true,
            'message' => 'Data Berhasil Diudapte!',
            'data'    => $product
        ]);
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

        return response()->json([
            'success' => true,
            'message' => 'Berhasil hapus',
        ]);
    }

    public function show($id)
    {
        $product = Product::with('category')->find($id);
        $categories = Category::all();

        return response()->json([
            'success' => true,
            'message' => 'Detail Data Post',
            'data'    => $product,
            'categories' => $categories,
        ]);
    }
}
