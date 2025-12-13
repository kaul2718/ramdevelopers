<?php
// filepath: app/Http/Requests/CityRequest.php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CityRequest extends FormRequest
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
        $id = $this->route('city');

        return [
            'city_name' => [
                'required',
                'string',
                'max:100',
                Rule::unique('cities', 'city_name')
                    ->ignore($id, 'city_id'),
            ],
            'city_region' => 'nullable|string|max:100',
            'ctry_id' => ['required', 'exists:countries,ctry_id'],
            'city_active' => ['required', 'boolean'],
        ];
    }

    public function messages(): array
    {
        return [
            'city_name.required' => 'El nombre de la ciudad es obligatorio.',
            'city_name.string' => 'El nombre debe ser un texto válido.',
            'city_name.max' => 'El nombre no puede exceder los 100 caracteres.',
            'city_name.unique' => 'La ciudad ya existe.',

            'city_region.string' => 'La región debe ser un texto válido.',
            'city_region.max' => 'La región no puede exceder los 100 caracteres.',

            'ctry_id.required' => 'El país es obligatorio.',
            'ctry_id.exists' => 'El país seleccionado no existe.',
        ];
    }
}