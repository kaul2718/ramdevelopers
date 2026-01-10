<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CommercialStatusRequest extends FormRequest
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
        $id = $this->route('commercialstatus');

        return [
            'commSta_name' => [
                'required',
                'string',
                'max:100',
                Rule::unique('commercial_statuses', 'commSta_name')
                    ->ignore($id, 'commSta_id'),
            ],
            'commSta_code' => [
                'required',
                'string',
                'max:50',
                Rule::unique('commercial_statuses', 'commSta_code')
                    ->ignore($id, 'commSta_id'),
            ],
            'commSta_description' => 'nullable|string',
            'commSta_active' => ['required', 'boolean'],
        ];
    }

    public function messages(): array
    {
        return [
            'commSta_name.required' => 'El nombre del estado es obligatorio.',
            'commSta_name.string' => 'El nombre debe ser un texto válido.',
            'commSta_name.max' => 'El nombre no puede exceder los 100 caracteres.',
            'commSta_name.unique' => 'El estado ya existe.',

            'commSta_code.required' => 'El código es obligatorio.',
            'commSta_code.string' => 'El código debe ser un texto válido.',
            'commSta_code.max' => 'El código no puede exceder los 50 caracteres.',
            'commSta_code.unique' => 'El código ya existe.',

            'commSta_description.string' => 'La descripción debe ser un texto.',
        ];
    }
}
