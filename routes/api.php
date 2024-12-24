<?php

use App\Http\Controllers\Api\v1\AuthController;
use App\Http\Controllers\Api\v1\ShoppingCartController;
use App\Http\Controllers\Api\v1\OrderController;
use App\Http\Controllers\Api\v1\ProductController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group( function () {
    
    // Auth
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);

    // routes protected by sanctum
    Route::middleware('auth:sanctum')->group(function () {
        Route::post('/logout', [AuthController::class, 'logout']);
        Route::get('/me', [AuthController::class, 'me']);
    });

    Route::middleware('auth:sanctum')->group(function () {
        Route::get('/shopping-cart', [ShoppingCartController::class, 'index']);
        Route::post('/shopping-cart/add', [ShoppingCartController::class, 'addItem']);
        Route::put('/shopping-cart/update/{cartItemId}', [ShoppingCartController::class, 'updateItem']);
        Route::delete('/shopping-cart/remove/{cartItemId}', [ShoppingCartController::class, 'removeItem']);
    });

    //products
    Route::prefix('products')->group( function () {
        Route::get('/search', [ProductController::class, 'search']);
        Route::get('/', [ProductController::class, 'index']);
        Route::post('/', [ProductController::class, 'store']);
        Route::get('/{id}', [ProductController::class, 'show']);
        Route::put('/{id}', [ProductController::class, 'update']);
        Route::delete('/{id}', [ProductController::class, 'destroy']);
        
    });
 
    Route::middleware('auth:sanctum')->prefix('products')->group(function () {
        Route::post('/order', [OrderController::class, 'createOrder']);
        Route::get('/orders', [OrderController::class, 'listOrders']);
        Route::get('/orders/{id}', [OrderController::class, 'orderDetails']);
    });


});
