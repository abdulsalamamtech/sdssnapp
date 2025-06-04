<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateCertificationRequestRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'management_note' => 'nullable|string|max:1000',
            'status' => 'required|in:pending,approved,rejected',
            // 'certification_id' => 'required|exists:certifications,id',
            // 'credential' => 'image|mimes:jpeg,png,pdf,jpg,gif,svg|max:20480', // Optional image upload for credential
            // 'signature' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048', // Optional image upload for signature
            // 'user_id' => 'required|exists:users,id',
            // 'requested_at' => 'required|date',
        ];
    }
}
