<?php

namespace App\Http\Controllers;

use App\Helper\ApiResponse;
use App\Models\Cart;
use App\Models\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $carts = Cart::with(['product.images', 'product.category'])
            ->where('user_id', auth()->id())
            ->get();

        return ApiResponse::success($carts);
    }

    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
        ]);

        $product = Product::select('id', 'stock')->findOrFail($request->product_id);

        if ($product->stock < $request->quantity) {
            return ApiResponse::error('Stok tidak mencukupi', 400);
        }

        $cart = Cart::updateOrCreate(
            [
                'user_id' => auth()->id(),
                'product_id' => $request->product_id,
            ],
            ['quantity' => $request->quantity]
        );

        return ApiResponse::success($cart->load('product'), 'Product added to cart');
    }

    public function update(Request $request, $id): JsonResponse
    {
        $request->validate([
            'quantity' => 'required|integer|min:1',
        ]);
        $cart = Cart::where('user_id', auth()->id())->findOrFail($id);

        $product = Product::select('id', 'stock')->findOrFail($cart->product_id);

        if ($product->stock < $request->quantity) {
            return ApiResponse::error('Stok tidak mencukupi', 400);
        }

        $cart->update(['quantity' => $request->quantity]);

        return ApiResponse::success($cart->load('product'), 'Cart updated successfully');
    }

    public function destroy($id): JsonResponse
    {
        $cart = Cart::where('user_id', auth()->id())->findOrFail($id);
        $cart->delete();

        return ApiResponse::success(null, 'Product removed from cart');
    }
}
