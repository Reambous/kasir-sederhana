<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Http\Requests\StoreProductRequest;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\UpdateProductRequest;
use Illuminate\Support\Facades\Storage;

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
        $data = $request->validated();

        // JIKA ADA FILE GAMBAR YANG DIUPLOAD
        if ($request->hasFile('gambar')) {
            // Simpan ke folder 'products' di dalam public storage
            $data['gambar'] = $request->file('gambar')->store('products', 'public');
        }

        $product = Product::create($data);

        if ($request->has('tags')) {
            $product->tags()->sync($request->tags);
        }

        return back()->with('success', 'Barang berhasil ditambahkan!');
    }

    // ... fungsi store yang sebelumnya sudah ada ...

    // Fungsi untuk memproses Update Data
    public function update(UpdateProductRequest $request, Product $product)
    {
        $data = $request->validated();

        // JIKA KITA MENGGANTI GAMBAR SAAT EDIT
        if ($request->hasFile('gambar')) {
            // Hapus gambar lama dari server (agar hardisk tidak penuh)
            if ($product->gambar) {
                Storage::disk('public')->delete($product->gambar);
            }
            // Simpan gambar baru
            $data['gambar'] = $request->file('gambar')->store('products', 'public');
        }

        $product->update($data);

        if ($request->has('tags')) {
            $product->tags()->sync($request->tags);
        } else {
            $product->tags()->detach();
        }

        return back()->with('success', 'Data barang berhasil diupdate!');
    }

    // Fungsi untuk memproses Hapus Data
    public function destroy(Product $product)
    {
        // Hapus juga gambar fisiknya saat barang dihapus
        if ($product->gambar) {
            Storage::disk('public')->delete($product->gambar);
        }

        $product->delete();
        return back()->with('success', 'Barang berhasil dihapus!');
    }
}
