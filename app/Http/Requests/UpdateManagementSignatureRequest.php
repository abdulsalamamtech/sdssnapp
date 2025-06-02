<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateManagementSignatureRequest extends FormRequest
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
            'title' => 'nullable|string|max:255',
            'full_name' => 'required|string|max:255',
            'position' => 'nullable|string|max:255',
            'signature' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048', // Assuming signature is an image file
        ];
    }
}
