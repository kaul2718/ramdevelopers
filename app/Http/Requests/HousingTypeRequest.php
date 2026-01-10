<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class HousingTypeRequest extends FormRequest
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
        $id = $this->route('housingtype');

        return [
            'houTyp_name' => [
                'required',
                'string',
                'max:100',
                Rule::unique('housing_types', 'houTyp_name')
                    ->ignore($id, 'houTyp_id'),
            ],
            'houTyp_code' => [
                'required',
                'string',
                'max:50',
                Rule::unique('housing_types', 'houTyp_code')
                    ->ignore($id, 'houTyp_id'),
            ],
            'houTyp_description' => 'nullable|string',
            'houTyp_active' => ['required', 'boolean'],
        ];
    }

    public function messages(): array
    {
        return [
            'houTyp_name.required' => 'El nombre del tipo de vivienda es obligatorio.',
            'houTyp_name.string' => 'El nombre debe ser un texto válido.',
            'houTyp_name.max' => 'El nombre no puede exceder los 100 caracteres.',
            'houTyp_name.unique' => 'Este nombre de tipo de vivienda ya existe.',
            'houTyp_code.required' => 'El código es obligatorio.',
            'houTyp_code.string' => 'El código debe ser un texto válido.',
            'houTyp_code.max' => 'El código no puede exceder los 50 caracteres.',
            'houTyp_code.unique' => 'Este código ya existe.',
            'houTyp_description.string' => 'La descripción debe ser un texto válido.',
            'houTyp_active.required' => 'El estado activo/inactivo es obligatorio.',
            'houTyp_active.boolean' => 'El estado debe ser verdadero o falso.',
        ];
    }
}
