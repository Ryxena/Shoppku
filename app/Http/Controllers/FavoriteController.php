<?php

namespace App\Http\Controllers;

use App\Helper\ApiResponse;
use App\Models\Favorite;
use Illuminate\Http\Request;

class FavoriteController extends Controller
{
    public function index()
    {
        $favorites = Favorite::with(['product.images', 'product.category'])
            ->where('user_id', auth()->id())
            ->get();
        return ApiResponse::success($favorites);
    }

    public function toggle(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id'
        ]);

        $favorite = Favorite::where([
            'user_id' => auth()->id(),
            'product_id' => $request->product_id
        ])->first();

        if ($favorite) {
            $favorite->delete();
            return ApiResponse::success(null, 'Product removed from favorites');
        } else {
            $favorite = Favorite::create([
                'user_id' => auth()->id(),
                'product_id' => $request->product_id
            ]);
            return ApiResponse::success($favorite, 'Product added to favorites');
        }
    }

}
