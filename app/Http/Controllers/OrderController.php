<?php

namespace App\Http\Controllers;

use App\Helper\ApiResponse;
use App\Models\Cart;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class OrderController extends Controller
{
    public function index()
    {
        $orders = Order::with(['orderDetails.product'])
            ->where('user_id', auth()->id())
            ->latest()
            ->get();
        return ApiResponse::success($orders);
    }

    public function show($id)
    {
        $order = Order::with(['orderDetails.product'])
            ->where('user_id', auth()->id())
            ->findOrFail($id);
        return ApiResponse::success($order);
    }

    public function store(Request $request)
    {
        try {
            $cartIds = $request->input('cart_ids');
            $userId = auth()->id();

            $carts = Cart::with('product')->where('user_id', $userId)->whereIn('id', $cartIds)->get();

            if ($carts->isEmpty()) {
                return ApiResponse::error('Cart not found');
            }

            $totalAmount = 0;

            foreach ($carts as $cart) {
                if ($cart->product->stock < $cart->quantity) {
                    return ApiResponse::error("Insufficient stock for product: {$cart->product->name}");
                }
                $totalAmount += $cart->product->price * $cart->quantity;
            }

            $order = Order::create([
                'user_id' => $userId,
                'order_number' => 'ORD-' . Str::random(10),
                'total_amount' => $totalAmount,
                'status' => 'pending',
                'checkout_date' => now()
            ]);

            foreach ($carts as $cart) {
                $order->orderDetails()->create([
                    'product_id' => $cart->product_id,
                    'quantity' => $cart->quantity,
                    'price' => $cart->product->price
                ]);

                $cart->product->decrement('stock', $cart->quantity);
            }

            Cart::where('user_id', $userId)->whereIn('id', $cartIds)->delete();

            return ApiResponse::success($order->load('orderDetails'), 'Order created successfully');
        } catch (\Exception $e) {
            return ApiResponse::error('Failed to create order', $e->getMessage());
        }
    }



    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:pending,processing,success,cancelled'
        ]);

        $order = Order::where('user_id', auth()->id())->findOrFail($id);
        $order->update(['status' => $request->status]);

        return ApiResponse::success($order, 'Order status updated successfully');
    }
}
