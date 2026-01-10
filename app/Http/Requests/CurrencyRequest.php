<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CurrencyRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $currencyId = $this->route('currency')?->curr_id;
        
        return [
            'curr_code' => 'required|string|max:3|unique:currencies,curr_code,' . ($currencyId ?? 'NULL') . ',curr_id',
            'curr_symbol' => 'required|string|max:10',
            'curr_name' => 'required|string|max:255|unique:currencies,curr_name,' . ($currencyId ?? 'NULL') . ',curr_id',
            'curr_symbol_first' => 'boolean',
            'curr_active' => 'boolean'
        ];
    }

    public function messages(): array
    {
        return [
            'curr_code.required' => 'El código de moneda es requerido',
            'curr_code.max' => 'El código no puede exceder 3 caracteres',
            'curr_code.unique' => 'Este código de moneda ya existe',
            'curr_symbol.required' => 'El símbolo es requerido',
            'curr_symbol.max' => 'El símbolo no puede exceder 10 caracteres',
            'curr_name.required' => 'El nombre de la moneda es requerido',
            'curr_name.max' => 'El nombre no puede exceder 255 caracteres',
            'curr_name.unique' => 'Este nombre de moneda ya existe',
        ];
    }
}
