<?php
// filepath: app/Http/Requests/LeadStatusRequest.php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class LeadStatusRequest extends FormRequest
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
        $id = $this->route('leadstatus');

        return [
            'leadSta_name' => [
                'required',
                'string',
                'max:100',
                Rule::unique('lead_statuses', 'leadSta_name')
                    ->ignore($id, 'leadSta_id'),
            ],
            'leadSta_code' => [
                'required',
                'string',
                'max:50',
                Rule::unique('lead_statuses', 'leadSta_code')
                    ->ignore($id, 'leadSta_id'),
            ],
            'leadSta_description' => 'nullable|string',
            'leadSta_active' => ['required', 'boolean'],
        ];
    }

    public function messages(): array
    {
        return [
            'leadSta_name.required' => 'El nombre del estado es obligatorio.',
            'leadSta_name.string' => 'El nombre debe ser un texto válido.',
            'leadSta_name.max' => 'El nombre no puede exceder los 100 caracteres.',
            'leadSta_name.unique' => 'El estado ya existe.',

            'leadSta_code.required' => 'El código es obligatorio.',
            'leadSta_code.string' => 'El código debe ser un texto válido.',
            'leadSta_code.max' => 'El código no puede exceder los 50 caracteres.',
            'leadSta_code.unique' => 'El código ya existe.',

            'leadSta_description.string' => 'La descripción debe ser un texto.',
        ];
    }
}