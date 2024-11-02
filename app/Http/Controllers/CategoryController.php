<?php

namespace App\Http\Controllers;

use App\Helper\ApiResponse;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::withCount('products')->get();
        return ApiResponse::success($categories);
    }

    public function show($id)
    {
        $category = Category::with('products.images')->findOrFail($id);
        return ApiResponse::success($category);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:categories'
        ]);

        $category = Category::create($request->all());
        return ApiResponse::success($category, 'Category created successfully');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:categories,name,' . $id
        ]);

        $category = Category::findOrFail($id);
        $category->update($request->all());

        return ApiResponse::success($category, 'Category updated successfully');
    }

    public function destroy($id)
    {
        $category = Category::findOrFail($id);
        $category->delete();

        return ApiResponse::success(null, 'Category deleted successfully');
    }
}
