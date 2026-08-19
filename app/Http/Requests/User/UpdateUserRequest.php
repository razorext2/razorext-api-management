<?php

namespace App\Http\Requests\User;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateUserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true; // Middleware handled this
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,'.$this->user->id,
            'password' => 'nullable|same:confirm-password|min:8',
            'roles' => 'required|array',
            'is_active' => 'required|boolean',
            'deactivation_reason' => 'required_if:is_active,0|nullable|string|max:255',
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     */
    public function messages(): array
    {
        return [
            'deactivation_reason.required_if' => 'Alasan nonaktif wajib diisi jika status diatur ke Tidak Aktif.',
        ];
    }
}
