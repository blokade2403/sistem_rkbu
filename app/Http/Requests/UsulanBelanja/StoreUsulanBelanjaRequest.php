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
            'sumber_dana_id' => [
                'required',
                'uuid',
                'exists:sumber_danas,id_sumber_dana',
            ],
            'jenis_kategori_rkbu_id' => [
                'required',
                'uuid',
                'exists:jenis_kategori_rkbus,id_jenis_kategori_rkbu',
            ],
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
            'sumber_dana_id.required' => 'Sumber dana wajib dipilih.',
            'jenis_kategori_rkbu_id.required' => 'Jenis kategori RKBU wajib dipilih.',
            'sub_kategori_rkbu_id.required' => 'Subkategori RKBU wajib dipilih.',
            'sub_kategori_rkbu_id.exists' => 'Data subkategori RKBU tidak ditemukan.',
        ];
    }
}
