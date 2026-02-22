<?php

namespace App\Http\Controllers;

use App\Models\Tag;
use App\Http\Requests\StoreTagRequest;

class TagController extends Controller
{
    public function store(StoreTagRequest $request)
    {
        // Jika kode sampai sini, artinya validasi sudah PASTI lolos.
        // Sistem otomatis melakukan "insert ke database"
        Tag::create($request->validated());

        // Kembali ke halaman sebelumnya dengan pesan sukses
        return back()->with('success', 'Tag berhasil ditambahkan!');
    }
}
