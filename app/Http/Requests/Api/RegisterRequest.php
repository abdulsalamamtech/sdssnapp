<?php

namespace App\Http\Requests\Api;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules;

class RegisterRequest extends FormRequest
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
            'name' => ['required', 'string', 'max:50', 'unique:' . User::class],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:' . User::class],
            'password' => ['required', 'confirmed', 'min:8', Rules\Password::defaults()],
            'first_name' => ['required', 'string'],
            'last_name' => ['required', 'string'],
            'security_question' => ['required', 'string'],
            'answer' => ['required', 'string'],
            'phone_number' => ['nullable', 'string'],
            'gender' => ['nullable', 'string', 'in:male,female'],
            'dob' => ['nullable', 'date'],
            'profession' => ['nullable','string'],
            'address' => ['nullable', 'string'],
            'city' => ['nullable', 'string'],
            'state' => ['nullable', 'string'],
            'country' => ['nullable', 'string'],
        ];
    }
}
