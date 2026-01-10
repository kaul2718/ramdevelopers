<?php
// filepath: app/Http/Requests/DevelopmentRequest.php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class DevelopmentRequest extends FormRequest
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
        $id = $this->route('development');

        return [
            'devr_id' => ['required', 'exists:developers,devr_id'],
            'ctry_id' => ['required', 'exists:countries,ctry_id'],
            'city_id' => ['required', 'exists:cities,city_id'],
            'apvSta_id' => ['required', 'exists:approval_statuses,apvSta_id'],
            'busiSta_id' => ['required', 'exists:business_state,busiSta_id'],
            'commSta_id' => ['required', 'exists:commercial_statuses,commSta_id'],
            'houTyp_id' => ['required', 'exists:housing_types,houTyp_id'],
            'curr_id' => ['nullable', 'exists:currencies,curr_id'],
            'devt_title' => [
                'required',
                'string',
                'max:255',
                Rule::unique('developments', 'devt_title')
                    ->ignore($id, 'devt_id'),
            ],
            'devt_slug' => [
                'required',
                'string',
                'max:255',
                Rule::unique('developments', 'devt_slug')
                    ->ignore($id, 'devt_id'),
            ],
            'devt_address' => [
                'required',
                'string',
                'max:255',
            ],
            'devt_short_description' => [
                'nullable',
                'string',
                'max:500',
            ],
            'devt_long_description' => [
                'nullable',
                'string',
            ],
            'devt_price_from' => [
                'nullable',
                'numeric',
                'min:0',
            ],
            'devt_price_to' => [
                'nullable',
                'numeric',
                'min:0',
            ],
            'devt_delivery_year' => [
                'nullable',
                'integer',
                'min:2020',
                'max:2100',
            ],
            'devt_estimated_profit' => [
                'nullable',
                'numeric',
                'min:0',
                function ($attribute, $value, $fail) {
                    $isPercentage = request('devt_estimated_profit_is_percentage');
                    if ($isPercentage && ($value < 1 || $value > 100)) {
                        $fail('Si es porcentaje, el valor debe estar entre 1 y 100.');
                    }
                }
            ],
            'devt_estimated_profit_is_percentage' => ['nullable', 'boolean'],
            'devt_storage_rooms' => ['nullable', 'integer', 'min:0'],
            'devt_parking_spaces' => ['nullable', 'integer', 'min:0'],
            'devt_terraces' => ['nullable', 'integer', 'min:0'],
            'devt_swimming_pools' => ['nullable', 'integer', 'min:0'],
            'devt_children_areas' => ['nullable', 'integer', 'min:0'],
            'devt_green_zones' => ['nullable', 'integer', 'min:0'],
            'devt_elevators' => ['nullable', 'integer', 'min:0'],
            'devt_golf_courses' => ['nullable', 'integer', 'min:0'],
            'devt_bedrooms' => ['nullable', 'integer', 'min:0'],
            'devt_is_featured' => ['nullable', 'boolean'],
            'devt_active' => ['nullable', 'boolean'],
        ];
    }

    public function messages(): array
    {
        return [
            'devr_id.required' => 'El desarrollador es obligatorio.',
            'devr_id.exists' => 'El desarrollador seleccionado no existe.',

            'ctry_id.required' => 'El país es obligatorio.',
            'ctry_id.exists' => 'El país seleccionado no existe.',

            'city_id.required' => 'La ciudad es obligatoria.',
            'city_id.exists' => 'La ciudad seleccionada no existe.',

            'apvSta_id.required' => 'El estado de aprobación es obligatorio.',
            'apvSta_id.exists' => 'El estado de aprobación seleccionado no existe.',

            'busiSta_id.required' => 'El estado de negocio es obligatorio.',
            'busiSta_id.exists' => 'El estado de negocio seleccionado no existe.',

            'commSta_id.required' => 'El estado comercial es obligatorio.',
            'commSta_id.exists' => 'El estado comercial seleccionado no existe.',

            'devt_title.required' => 'El título del desarrollo es obligatorio.',
            'devt_title.string' => 'El título debe ser un texto válido.',
            'devt_title.max' => 'El título no puede exceder los 255 caracteres.',
            'devt_title.unique' => 'El título ya existe.',

            'devt_slug.required' => 'El slug es obligatorio.',
            'devt_slug.string' => 'El slug debe ser un texto válido.',
            'devt_slug.max' => 'El slug no puede exceder los 255 caracteres.',
            'devt_slug.unique' => 'El slug ya existe.',

            'devt_address.required' => 'La dirección es obligatoria.',
            'devt_address.string' => 'La dirección debe ser un texto válido.',
            'devt_address.max' => 'La dirección no puede exceder los 255 caracteres.',

            'devt_short_description.string' => 'La descripción corta debe ser un texto válido.',
            'devt_short_description.max' => 'La descripción corta no puede exceder los 500 caracteres.',

            'devt_price_from.numeric' => 'El precio inicial debe ser un número.',
            'devt_price_from.min' => 'El precio inicial no puede ser negativo.',

            'devt_price_to.numeric' => 'El precio final debe ser un número.',
            'devt_price_to.min' => 'El precio final no puede ser negativo.',

            'devt_delivery_year.integer' => 'El año de entrega debe ser un número.',
            'devt_delivery_year.min' => 'El año de entrega no puede ser menor a 2020.',
            'devt_delivery_year.max' => 'El año de entrega no puede ser mayor a 2100.',

            'devt_estimated_profit.numeric' => 'Los honorarios estimados deben ser un número.',
            'devt_estimated_profit.min' => 'Los honorarios estimados no pueden ser negativos.',

            'devt_is_featured.boolean' => 'El campo destacado debe ser un valor booleano.',

            'devt_active.boolean' => 'El estado activo debe ser un valor booleano.',
        ];
    }
}
