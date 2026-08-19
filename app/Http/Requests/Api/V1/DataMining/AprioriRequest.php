<?php

namespace App\Http\Requests\Api\V1\DataMining;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class AprioriRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'transactions' => 'required|array|min:1',
            'transactions.*' => 'required|array|min:1',
            'transactions.*.*' => 'required|string|max:255',
            'min_support' => 'nullable|numeric|between:0.0001,1.0',
            'min_confidence' => 'nullable|numeric|between:0.0001,1.0',
        ];
    }

    public function messages(): array
    {
        return [
            'transactions.required' => 'Daftar transaksi wajib dikirimkan dalam format array of array.',
            'transactions.min' => 'Transaksi minimal berisi 1 baris.',
            'min_support.between' => 'Nilai min_support harus berupa desimal antara 0.0001 dan 1.0 (contoh: 0.2 untuk 20%).',
            'min_confidence.between' => 'Nilai min_confidence harus berupa desimal antara 0.0001 dan 1.0 (contoh: 0.6 untuk 60%).',
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'success' => false,
            'status' => 422,
            'message' => 'Validasi parameter Apriori gagal.',
            'errors' => $validator->errors(),
        ], 422));
    }
}
