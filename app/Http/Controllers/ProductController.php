<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Http\Requests\StoreProductRequest;
use App\Services\ObjectStorage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\UpdateProductRequest;

class ProductController extends Controller
{
    public function index()
    {
        return view('products.index');
    }

    public function store(StoreProductRequest $request, ObjectStorage $storage)
    {
        $data = $request->validated();
        unset($data['gambar']);

        $object = null;

        if ($request->hasFile('gambar')) {
            $object = $storage->put($request->file('gambar'), 'products', Auth::user());
            $data['gambar_object_id'] = $object->getKey();
        }

        $data['tanggal_masuk'] = now();
        $data['user_id'] = Auth::id();

        try {
            $product = Product::create($data);
        } catch (\Throwable $e) {
            $storage->delete($object);

            throw $e;
        }

        if ($request->has('tags')) {
            $product->tags()->sync($request->tags);
        }

        return back()->with('success', 'Barang berhasil ditambahkan!');
    }

    public function update(UpdateProductRequest $request, Product $product, ObjectStorage $storage)
    {
        $data = $request->validated();
        unset($data['gambar']);

        $oldObject = $product->gambar;
        $newObject = null;

        if ($request->hasFile('gambar')) {
            $newObject = $storage->put($request->file('gambar'), 'products', Auth::user());
            $data['gambar_object_id'] = $newObject->getKey();
        }

        try {
            DB::transaction(function () use ($product, $data, $request) {
                $product->update($data);

                if ($request->has('tags')) {
                    $product->tags()->sync($request->tags);
                } else {
                    $product->tags()->detach();
                }
            });
        } catch (\Throwable $e) {
            $storage->delete($newObject);

            throw $e;
        }

        $product->unsetRelation('gambar');

        if ($oldObject && $newObject) {
            $storage->delete($oldObject);
        }

        return back()->with('success', 'Data barang berhasil diupdate!');
    }

    public function destroy(Product $product)
    {
        $product->delete();

        return back()->with('success', 'Barang berhasil dihapus!');
    }
}
