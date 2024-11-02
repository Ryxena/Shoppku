<?php

use App\Http\Controllers\CartController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\FavoriteController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function () {
    // Auth routes
    Route::controller(UserController::class)->prefix('auth')->group(function () {
        Route::post('register', 'register')->name('register');
        Route::post('login', 'login')->name('login');
        Route::get('logout', 'logout')->name('logout');
        Route::post('edit', 'edit')->name('edit');
    });

    Route::middleware('auth:sanctum')->group(function () {
        // Product routes
        Route::controller(ProductController::class)->prefix('products')->group(function () {
            Route::get('/', 'index');
            Route::get('{id}', 'show');
            Route::get('/category/{categoryId}', 'byCategory');
            Route::post('/', 'store');
            Route::post('{id}', 'update');
            Route::delete('{id}', 'destroy');
        });

        // Category routes
        Route::controller(CategoryController::class)->prefix('categories')->group(function () {
            Route::get('/', 'index');
            Route::get('{id}', 'show');
            Route::post('/', 'store');
            Route::post('{id}', 'update');
            Route::delete('{id}', 'destroy');
        });

        // Cart routes
        Route::controller(CartController::class)->prefix('cart')->group(function () {
            Route::get('/', 'index');
            Route::post('add', 'store');
            Route::post('update/{id}', 'update');
            Route::post('remove/{id}', 'destroy');
        });

        // Order routes
        Route::controller(OrderController::class)->prefix('orders')->group(function () {
            Route::get('/', 'index');
            Route::get('{id}', 'show');
            Route::post('checkout/', 'store');
            Route::post('status/{id}', 'updateStatus');
        });

        // Favorite routes
        Route::controller(FavoriteController::class)->prefix('favorites')->group(function () {
            Route::get('/', 'index');
            Route::post('toggle', 'toggle');
        });
    });
});
