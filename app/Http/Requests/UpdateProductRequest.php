<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProductRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        // Mengambil ID produk yang sedang diedit dari URL Route
        $productId = $this->route('product')->id;

        return [
            'nama'       => ['required', 'string', 'max:255'],
            // Perhatikan bagian unique di bawah ini, kita beri pengecualian untuk ID saat ini
            'barcode'    => ['required', 'string', 'max:50', 'unique:products,barcode,' . $productId],
            'harga_beli' => ['required', 'integer', 'min:0'],
            'harga_jual' => ['required', 'integer', 'min:0'],
            'jumlah'     => ['required', 'integer', 'min:0'],
            'tags'       => ['nullable', 'array'],
            'tags.*'     => ['exists:tags,id'],
            'gambar'     => ['nullable', 'image', 'max:2048']
        ];
    }

    public function messages(): array
    {
        return [
            'required' => 'Data nama/barcode/harga/qty wajib diisi',
            'barcode.unique' => 'Barcode ini sudah terdaftar pada barang lain',
        ];
    }
}
