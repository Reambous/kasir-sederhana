<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TagController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\StockOpnameController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Halaman depan (Welcome)
Route::view('/', 'welcome');

// Halaman Dashboard bawaan Breeze (Mesin Kasir kita tampil di sini)
// Halaman Dashboard dengan Statistik
Route::get('/dashboard', function () {
    $totalProducts = \App\Models\Product::count();
    $totalOrders = \App\Models\Order::whereDate('tanggal', now()->toDateString())->count();
    $lowStock = \App\Models\Product::where('jumlah', '<', 10)->count();

    return view('dashboard', compact('totalProducts', 'totalOrders', 'lowStock'));
})->middleware(['auth', 'verified'])->name('dashboard');

// Halaman Khusus Mesin Kasir
Route::view('/pos', 'pos')
    ->middleware(['auth'])
    ->name('pos');

// Halaman Profile bawaan Breeze
Route::view('profile', 'profile')
    ->middleware(['auth'])
    ->name('profile');


/*
|--------------------------------------------------------------------------
| POS System Routes (Dilindungi oleh Auth / Wajib Login)
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {

    // --- ROUTE GUDANG (BARANG & TAG) ---
    Route::get('/products', [ProductController::class, 'index'])->name('products.index');
    Route::post('/products', [ProductController::class, 'store'])->name('products.store');
    Route::put('/products/{product}', [ProductController::class, 'update'])->name('products.update');
    Route::delete('/products/{product}', [ProductController::class, 'destroy'])->name('products.destroy');

    // --- ROUTE TAG (KATEGORI) ---
    Route::get('/tags', [TagController::class, 'index'])->name('tags.index');
    Route::post('/tags', [TagController::class, 'store'])->name('tags.store');
    Route::delete('/tags/{tag}', [TagController::class, 'destroy'])->name('tags.destroy');
    Route::put('/tags/{tag}', [TagController::class, 'update'])->name('tags.update');
    // --- ROUTE KASIR (TRANSAKSI & INVOICE) ---
    Route::post('/orders', [OrderController::class, 'store'])->name('orders.store');
    Route::get('/orders/{order}/export', [OrderController::class, 'export'])->name('orders.export');


    // --- ROUTE STOCK OPNAME ---

    Route::get('/stock-opnames', [StockOpnameController::class, 'index'])->name('stock-opnames.index'); // <-- TAMBAHKAN BARIS INI

    Route::post('/stock-opnames', [StockOpnameController::class, 'store'])->name('stock-opnames.store');
    Route::post('/stock-opnames/{stockOpname}/sync', [StockOpnameController::class, 'sync'])->name('stock-opnames.sync');
    Route::put('/so-products/{soProduct}', [StockOpnameController::class, 'updateItem'])->name('so-products.update');
    Route::post('/stock-opnames/{stockOpname}/finish', [StockOpnameController::class, 'finish'])->name('stock-opnames.finish');
    Route::delete('/stock-opnames/{stockOpname}/cancel', [StockOpnameController::class, 'cancel'])->name('stock-opnames.cancel');
    Route::put('/stock-opnames/{stockOpname}/update-all', [StockOpnameController::class, 'updateAllItems'])->name('stock-opnames.updateAll');
});

// Memuat route otentikasi (login, register, dll) bawaan Breeze
require __DIR__ . '/auth.php';
