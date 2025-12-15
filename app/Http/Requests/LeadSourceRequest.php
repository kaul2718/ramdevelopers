<?php
// filepath: app/Http/Requests/LeadSourceRequest.php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class LeadSourceRequest extends FormRequest
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
        $id = $this->route('leadsource');

        return [
            'leadSou_name' => [
                'required',
                'string',
                'max:100',
                Rule::unique('lead_sources', 'leadSou_name')
                    ->ignore($id, 'leadSou_id'),
            ],
            'leadSou_code' => [
                'required',
                'string',
                'max:50',
                Rule::unique('lead_sources', 'leadSou_code')
                    ->ignore($id, 'leadSou_id'),
            ],
            'leadSou_description' => 'nullable|string',
            'leadSou_active' => ['required', 'boolean'],
        ];
    }

    public function messages(): array
    {
        return [
            'leadSou_name.required' => 'El nombre de la fuente es obligatorio.',
            'leadSou_name.string' => 'El nombre debe ser un texto válido.',
            'leadSou_name.max' => 'El nombre no puede exceder los 100 caracteres.',
            'leadSou_name.unique' => 'La fuente ya existe.',

            'leadSou_code.required' => 'El código es obligatorio.',
            'leadSou_code.string' => 'El código debe ser un texto válido.',
            'leadSou_code.max' => 'El código no puede exceder los 50 caracteres.',
            'leadSou_code.unique' => 'El código ya existe.',

            'leadSou_description.string' => 'La descripción debe ser un texto.',
        ];
    }
}