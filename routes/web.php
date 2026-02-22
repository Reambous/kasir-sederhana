<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TagController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\OrderController;

Route::get('/', function () {
    return view('welcome');
});
// Route untuk Tag
Route::post('/tags', [TagController::class, 'store'])->name('tags.store');

// Route untuk Produk
Route::post('/products', [ProductController::class, 'store'])->name('products.store');

Route::post('/orders', [OrderController::class, 'store'])->name('orders.store');
