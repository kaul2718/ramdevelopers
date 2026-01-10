<?php
// filepath: app/Http/Requests/DevelopmentCaptorRequest.php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DevelopmentCaptorRequest extends FormRequest
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
        return [
            'user_id' => ['required', 'exists:users,id', 'integer'],
            'devtUsr_is_main' => ['nullable', 'boolean'],
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'user_id.required' => 'El usuario es obligatorio.',
            'user_id.exists' => 'El usuario seleccionado no existe.',
            'user_id.integer' => 'El ID del usuario debe ser un número entero.',
            'devtUsr_is_main.boolean' => 'El campo captador principal debe ser un booleano.',
        ];
    }
}
