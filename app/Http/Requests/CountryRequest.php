<?php
// filepath: app/Http/Requests/CountryRequest.php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CountryRequest extends FormRequest
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
     */
    public function rules(): array
    {
        $id = $this->route('country');

        return [
            'ctry_name' => [
                'required',
                'string',
                'max:100',
                Rule::unique('countries', 'ctry_name')
                    ->ignore($id, 'ctry_id'),
            ],
            'ctry_code' => [
                'required',
                'string',
                'max:5',
                Rule::unique('countries', 'ctry_code')
                    ->ignore($id, 'ctry_id'),
            ],
            'ctry_active' => ['required', 'boolean'],
        ];
    }

    public function messages(): array
    {
        return [
            'ctry_name.required' => 'El nombre del país es obligatorio.',
            'ctry_name.string' => 'El nombre debe ser un texto válido.',
            'ctry_name.max' => 'El nombre no puede exceder los 100 caracteres.',
            'ctry_name.unique' => 'El país ya existe.',

            'ctry_code.required' => 'El código es obligatorio.',
            'ctry_code.string' => 'El código debe ser un texto válido.',
            'ctry_code.max' => 'El código no puede exceder los 5 caracteres.',
            'ctry_code.unique' => 'El código ya existe.',
        ];
    }
}