<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Http\Requests\StoreProductRequest;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\UpdateProductRequest;

class ProductController extends Controller
{
    public function index()
    {
        // Mengambil semua produk diurutkan dari yang terbaru
        $products = \App\Models\Product::latest()->get();

        // Mengambil tag jika nanti ingin dikembangkan
        $tags = \App\Models\Tag::all();

        return view('products.index', compact('products', 'tags'));
    }

    public function store(StoreProductRequest $request)
    {

        // Menggunakan DB::transaction untuk keamanan. 
        // Jika gagal di tengah jalan (misal gagal simpan tag pivot), semua data dibatalkan (rollback).
        DB::transaction(function () use ($request) {

            // 1. Persiapkan data yang akan diinsert
            $data = $request->validated();

            // Set tanggal masuk otomatis sesuai hari ini
            $data['tanggal_masuk'] = now()->toDateString();

            // Set user_id (sementara kita asumsikan user ID 1 yang sedang login jika belum ada fitur Auth penuh)
            $data['user_id'] = Auth::id() ?? '123e4567-e89b-12d3-a456-426614174000'; // Nanti sesuaikan dengan UUID user aslimu

            // Hapus 'tags' dari array $data karena tabel products tidak punya kolom 'tags'
            unset($data['tags']);

            // 2. Insert ke tabel products
            $product = Product::create($data);

            // 3. Insert ke tabel relasi product_tag jika ada tag yang dipilih
            if ($request->has('tags')) {
                // attach() adalah cara efisien Laravel untuk memasukkan data ke tabel pivot (many-to-many)
                $product->tags()->attach($request->tags);
            }
        });

        return back()->with('success', 'Barang berhasil ditambahkan!');
    }

    // ... fungsi store yang sebelumnya sudah ada ...

    // Fungsi untuk memproses Update Data
    public function update(UpdateProductRequest $request, Product $product)
    {
        // Kita pakai DB::transaction lagi untuk keamanan update tabel relasi
        DB::transaction(function () use ($request, $product) {
            $data = $request->validated();

            // Pisahkan tags karena tidak ada di tabel products
            $tags = $data['tags'] ?? [];
            unset($data['tags']);

            // 1. Update data utama di tabel products
            $product->update($data);

            // 2. Update relasi tag. 
            // Menggunakan sync() adalah cara pintar Laravel. Ini akan menghapus tag lama 
            // dan menggantinya dengan tag baru secara otomatis.
            $product->tags()->sync($tags);
        });

        return back()->with('success', 'Data barang berhasil diperbarui!');
    }

    // Fungsi untuk memproses Hapus Data
    public function destroy(Product $product)
    {
        // Berkat pengaturan 'cascadeOnDelete' di migration kita sebelumnya,
        // saat barang dihapus, data di tabel pivot (products_tags) akan otomatis terhapus juga!
        $product->delete();

        return back()->with('success', 'Barang berhasil dihapus!');
    }
}
