<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Http\Requests\StoreProductRequest;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class ProductController extends Controller
{
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
}
