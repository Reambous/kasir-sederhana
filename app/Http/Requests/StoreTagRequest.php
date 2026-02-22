<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreTagRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // Izinkan semua request untuk saat ini
    }

    public function rules(): array
    {
        return [
            'nama' => ['required', 'string', 'max:255', 'unique:tags,nama']
            // unique memastikan tidak ada tag dengan nama ganda
        ];
    }

    public function messages(): array
    {
        // Custom pesan error sesuai flowchart kamu
        return [
            'nama.required' => 'Nama wajib diisi',
            'nama.unique' => 'Nama tag ini sudah digunakan',
        ];
    }
}
