<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateCertificationRequest extends FormRequest
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
            'management_signature_id' => 'required|exists:management_signatures,id',
            'secretary_signature_id' => 'required|exists:management_signatures,id',
            'organization_name' => 'nullable|string|max:255',
            'title' => 'nullable|string|max:255',
            'type' => 'nullable|string|max:255',
            'abbreviation_code' => 'nullable|string|min:3|max:3',
            // individual or organization
            'for' => 'nullable|in:individual,organization',
            'duration' => 'nullable|integer|min:1',
            'amount' => 'required|numeric|min:0',
            'duration_unit' => 'nullable|string|in:weeks,months,years',
            'initial_amount' => 'nullable|numeric|min:0',

            'requirements' => 'nullable|string',
            'benefits' => 'nullable|string',
            'targets' => 'nullable|string',
            // 'created_by' => 'nullable|exists:users,id',
            // 'updated_by' => 'nullable|exists:users,id',
            // 'deleted_by' => 'nullable|exists:users,id',
        ];
    }
}
