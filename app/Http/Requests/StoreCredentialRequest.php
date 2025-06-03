<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreCredentialRequest extends FormRequest
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
            'title' => 'required|unique:credentials,title|string|max:255',
            'type' => 'required|string|in:Professional,Academic', // Assuming these are the only two types
            'description' => 'nullable|string|max:1000',
            'file' => ['required', 'image|mimes:jpeg,png,jpg,pdf,gif,svg|max:2048'],
            // 'file_id' => 'required|exists:assets,id', // Assuming assets table has an id column
            // 'created_by' => 'nullable|exists:users,id', // Assuming users table has an id column
        ];
    }
}
