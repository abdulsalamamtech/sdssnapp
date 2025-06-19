<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreCertificationRequestRequest extends FormRequest
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
            'certification_id' => 'required|exists:certifications,id',
            'reason_for_certification' => 'nullable|string|max:1000',
            'credential' => 'file|mimes:jpeg,png,pdf,jpg,gif|max:20480', // Optional file upload for credential
            // 'signature' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048', // Optional image upload for signature
            // 'user_id' => 'required|exists:users,id',
            // 'status' => 'required|in:pending,approved,rejected',
            // 'requested_at' => 'required|date',
        ];
    }
}
