<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function getAllCategories(Request $request)
    {
        $term = $request->input('term'); // The user's input

        $categories = Category::where('name', 'LIKE', "%$term%")->get();

        $data = [];

        foreach ($categories as $category) {
            $data[] = [
                'id' => $category->id,
                'text' => $category->name,
            ];
        }

        return response()->json($data);
    }
}
