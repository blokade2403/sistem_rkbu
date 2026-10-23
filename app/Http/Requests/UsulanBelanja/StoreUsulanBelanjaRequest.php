<?php

namespace App\Http\Requests\UsulanBelanja;

use Illuminate\Foundation\Http\FormRequest;

class StoreUsulanBelanjaRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        return [
            'sub_kategori_rkbu_id' => [
                'required',
                'uuid',
                'exists:sub_kategori_rkbus,id_sub_kategori_rkbu',
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'sub_kategori_rkbu_id.required' => 'Subkategori RKBU wajib dipilih.',
            'sub_kategori_rkbu_id.exists' => 'Data subkategori RKBU tidak ditemukan.',
        ];
    }
}
