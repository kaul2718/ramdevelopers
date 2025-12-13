<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class BusinessStateRequest extends FormRequest
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
        $id = $this->route('businessstate');

        return [
            'busiSta_name' => [
                'required',
                'string',
                'max:100',
                Rule::unique('business_state', 'busiSta_name')
                    ->ignore($id, 'busiSta_id'),
            ],
            'busiSta_code' => [
                'required',
                'string',
                'max:50',
                Rule::unique('business_state', 'busiSta_code')
                    ->ignore($id, 'busiSta_id'),
            ],
            'busiSta_description' => 'nullable|string',
            'busiSta_active' => ['nullable', 'boolean'],
        ];
    }

    public function messages(): array
    {
        return [
            'busiSta_name.required' => 'El nombre del estado es obligatorio.',
            'busiSta_name.string' => 'El nombre debe ser un texto válido.',
            'busiSta_name.max' => 'El nombre no puede exceder los 100 caracteres.',
            'busiSta_name.unique' => 'El estado ya existe.',

            'busiSta_code.required' => 'El código es obligatorio.',
            'busiSta_code.string' => 'El código debe ser un texto válido.',
            'busiSta_code.max' => 'El código no puede exceder los 50 caracteres.',
            'busiSta_code.unique' => 'El código ya existe.',

            'busiSta_description.string' => 'La descripción debe ser un texto.',
        ];
    }
}