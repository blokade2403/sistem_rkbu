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
            'rkbu_id' => [
                'required',
                'uuid',
                'exists:rkbus,id',
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'rkbu_id.required' => 'RKBU wajib dipilih.',
            'rkbu_id.exists' => 'Data RKBU tidak ditemukan.',
        ];
    }
}
