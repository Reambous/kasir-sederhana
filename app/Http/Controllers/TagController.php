<?php

namespace App\Http\Controllers;

use App\Models\Tag;
use App\Http\Requests\StoreTagRequest;
use App\Http\Requests\UpdateTagRequest;

class TagController extends Controller
{
    // Menampilkan halaman daftar Tag
    public function index()
    {
        return view('tags.index');
    }

    // Fungsi menyimpan Tag (Sudah kita buat sebelumnya)
    public function store(StoreTagRequest $request)
    {
        Tag::create($request->validated());
        return back()->with('success', 'Kategori/Tag berhasil ditambahkan!');
    }

    // Fungsi untuk memproses Update Data Tag
    public function update(UpdateTagRequest $request, Tag $tag)
    {
        $tag->update($request->validated());
        return back()->with('success', 'Kategori/Tag berhasil diperbarui!');
    }
    // Fungsi menghapus Tag
    public function destroy(Tag $tag)
    {
        $tag->delete();
        return back()->with('success', 'Kategori/Tag berhasil dihapus!');
    }
}
