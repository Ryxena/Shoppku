<?php

namespace App\Http\Controllers;

use App\Helper\ApiResponse;
use App\Models\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::with(['category', 'images']);

        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%")
                    ->orWhereHas('category', function ($q) use ($search) {
                        $q->where('name', 'like', "%{$search}%");
                    });
            });
        }

        if ($request->has('categories')) {
            $categories = explode(',', $request->categories);
            if (!in_array('all', array_map('strtolower', $categories))) {
                $query->whereIn('category_id', $categories);
            }
        }

        if ($request->has('sort')) {
            switch ($request->sort) {
                case 'name_asc':
                    $query->orderBy('name', 'asc');
                    break;
                case 'name_desc':
                    $query->orderBy('name', 'desc');
                    break;
                case 'price_asc':
                    $query->orderBy('price', 'asc');
                    break;
                case 'price_desc':
                    $query->orderBy('price', 'desc');
                    break;
                case 'rating_asc':
                    $query->orderBy('rating', 'asc');
                    break;
                case 'rating_desc':
                    $query->orderBy('rating', 'desc');
                    break;
                default:
                    $query->latest();
                    break;
            }
        }

        $products = $query->get();
        return ApiResponse::success($products);
    }

    public function byCategory($categoryId)
    {
        $query = Product::with(['category', 'images']);

        if (strtolower($categoryId) !== 'all') {
            $query->where('category_id', $categoryId);
        }

        $products = $query->get();
        return ApiResponse::success($products);
    }

    public function show($id): JsonResponse
    {
        $product = Product::with(['category', 'images'])->findOrFail($id);
        return ApiResponse::success($product);
    }

    public function store(Request $request)
    {
        $request->validate([
            'category_id' => 'required|exists:categories,id',
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'stock' => 'required|integer|min:0',
            'price' => 'required|numeric|min:0',
            'images.*' => 'required|image|mimes:jpeg,png,jpg|max:2048'
        ]);

        try {
            $product = Product::create($request->except('images'));

            if ($request->hasFile('images')) {
                foreach ($request->file('images') as $image) {
                    $fileName = time() . '_' . $image->getClientOriginalName();
                    $image->storeAs('public/products', $fileName);

                    $product->images()->create([
                        'image_path' => 'products/' . $fileName
                    ]);
                }
            }

            return ApiResponse::success($product->load('images'), 'Product created successfully');
        } catch (\Exception $e) {
            return ApiResponse::error('Failed to create product', $e->getMessage());
        }
    }

    public function update(Request $request, $id): JsonResponse
    {
        $product = Product::findOrFail($id);

        $request->validate([
            'category_id' => 'exists:categories,id',
            'name' => 'string|max:255',
            'description' => 'string',
            'stock' => 'integer|min:0',
            'price' => 'numeric|min:0',
            'images.*' => 'image|mimes:jpeg,png,jpg|max:2048'
        ]);

        try {
            $product->update($request->except('images'));

            if ($request->hasFile('images')) {
                foreach ($product->images as $image) {
                    Storage::delete('public/' . $image->image_path);
                }
                $product->images()->delete();

                foreach ($request->file('images') as $image) {
                    $fileName = time() . '_' . $image->getClientOriginalName();
                    $image->storeAs('public/products', $fileName);

                    $product->images()->create([
                        'image_path' => 'products/' . $fileName
                    ]);
                }
            }

            return ApiResponse::success($product->load('images'), 'Product updated successfully');
        } catch (\Exception $e) {
            return ApiResponse::error('Failed to update product', $e->getMessage());
        }
    }

    public function destroy($id): JsonResponse
    {
        $product = Product::findOrFail($id);

        try {
            foreach ($product->images as $image) {
                Storage::delete('public/' . $image->image_path);
            }

            $product->delete();

            return ApiResponse::success(null, 'Product deleted successfully');
        } catch (\Exception $e) {
            return ApiResponse::error('Failed to delete product', $e->getMessage());
        }
    }
}
