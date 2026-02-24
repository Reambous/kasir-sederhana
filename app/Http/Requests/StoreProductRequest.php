<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreProductRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'nama'       => ['required', 'string', 'max:255'],
            'barcode'    => ['required', 'string', 'max:50', 'unique:products,barcode'],
            'harga_beli' => ['required', 'integer', 'min:0'],
            'harga_jual' => ['required', 'integer', 'min:0'],
            'jumlah'     => ['required', 'integer', 'min:0'],
            'tags'       => ['nullable', 'array'], // Tag boleh kosong (nullable)
            'tags.*'     => ['exists:tags,id'],    // Pastikan ID tag benar-benar ada di database
            'gambar'     => ['nullable', 'image', 'mimes:jpeg,png,jpg', 'max:2048'] // Maksimal 2MB jika ada gambar
        ];
    }

    public function messages(): array
    {
        return [
            'required' => 'Data nama/barcode/harga/qty wajib diisi',
            'barcode.unique' => 'Barcode ini sudah terdaftar di sistem',
        ];
    }
}
