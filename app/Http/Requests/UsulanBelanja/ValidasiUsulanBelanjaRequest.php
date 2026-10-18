<?php

namespace App\Http\Requests\UsulanBelanja;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ValidasiUsulanBelanjaRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        return [
            'aksi' => [
                'required',
                Rule::in(['approved', 'revision', 'rejected']),
            ],
            'catatan' => [
                'nullable',
                'string',
                'max:5000',
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'aksi.required' => 'Keputusan validasi wajib dipilih.',
            'aksi.in' => 'Keputusan validasi tidak valid.',
        ];
    }
}
