<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateTagRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        // Mengambil ID tag yang sedang diedit agar namanya tidak dianggap duplikat dengan dirinya sendiri
        $tagId = $this->route('tag')->id;

        return [
            'nama' => ['required', 'string', 'max:255', 'unique:tags,nama,' . $tagId]
        ];
    }
}
