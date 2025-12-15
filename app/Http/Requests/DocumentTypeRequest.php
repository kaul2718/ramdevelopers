<?php
// filepath: app/Http/Requests/DocumentTypeRequest.php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class DocumentTypeRequest extends FormRequest
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
        $id = $this->route('documenttype');

        return [
            'docTyp_name' => [
                'required',
                'string',
                'max:100',
                Rule::unique('document_types', 'docTyp_name')
                    ->ignore($id, 'docTyp_id'),
            ],
            'docTyp_code' => [
                'required',
                'string',
                'max:50',
                Rule::unique('document_types', 'docTyp_code')
                    ->ignore($id, 'docTyp_id'),
            ],
            'docTyp_description' => 'nullable|string',
            'docTyp_isInternal' => ['required', 'boolean'],
            'docTyp_active' => ['required', 'boolean'],
        ];
    }

    public function messages(): array
    {
        return [
            'docTyp_name.required' => 'El nombre del tipo de documento es obligatorio.',
            'docTyp_name.string' => 'El nombre debe ser un texto válido.',
            'docTyp_name.max' => 'El nombre no puede exceder los 100 caracteres.',
            'docTyp_name.unique' => 'El tipo de documento ya existe.',

            'docTyp_code.required' => 'El código es obligatorio.',
            'docTyp_code.string' => 'El código debe ser un texto válido.',
            'docTyp_code.max' => 'El código no puede exceder los 50 caracteres.',
            'docTyp_code.unique' => 'El código ya existe.',

            'docTyp_description.string' => 'La descripción debe ser un texto.',
            
            'docTyp_isInternal.required' => 'Debe especificar si es interno.',
        ];
    }
}