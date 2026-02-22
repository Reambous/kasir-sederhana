<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreOrderRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'nama_buyer'        => ['nullable', 'string', 'max:255'],
            'metode_pembayaran' => ['required', 'in:cash,non_cash'], // Harus sesuai enum di database
            'potongan'          => ['nullable', 'integer', 'min:0'],
            'uang_diterima'     => ['required', 'integer', 'min:0'],

            // Validasi untuk array barang belanjaan
            'items'             => ['required', 'array', 'min:1'], // Minimal 1 barang
            'items.*.product_id' => ['required', 'exists:products,id'], // ID barang harus ada di DB
            'items.*.jumlah'    => ['required', 'integer', 'min:1'], // Beli minimal 1 qty
        ];
    }
}
