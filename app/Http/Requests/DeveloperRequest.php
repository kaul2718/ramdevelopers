<?php
// filepath: app/Http/Requests/DeveloperRequest.php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class DeveloperRequest extends FormRequest
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
        $id = $this->route('developer');

        return [
            'devr_commercial_name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('developers', 'devr_commercial_name')
                    ->ignore($id, 'devr_id'),
            ],
            'devr_legal_name' => [
                'required',
                'string',
                'max:255',
            ],
            'devr_email_contact' => [
                'required',
                'email',
                'max:255',
                Rule::unique('developers', 'devr_email_contact')
                    ->ignore($id, 'devr_id'),
            ],
            'devr_phone_contact' => [
                'required',
                'string',
                'max:20',
            ],
            'devr_website' => [
                'nullable',
                'url',
                'max:255',
            ],
            'ctry_id' => ['required', 'exists:countries,ctry_id'],
            'user_id' => ['required', 'exists:users,id'],
            'devr_active' => ['nullable', 'boolean'],
        ];
    }

    public function messages(): array
    {
        return [
            'devr_commercial_name.required' => 'El nombre comercial del desarrollador es obligatorio.',
            'devr_commercial_name.string' => 'El nombre comercial debe ser un texto válido.',
            'devr_commercial_name.max' => 'El nombre comercial no puede exceder los 255 caracteres.',
            'devr_commercial_name.unique' => 'El nombre comercial ya existe.',

            'devr_legal_name.required' => 'El nombre legal del desarrollador es obligatorio.',
            'devr_legal_name.string' => 'El nombre legal debe ser un texto válido.',
            'devr_legal_name.max' => 'El nombre legal no puede exceder los 255 caracteres.',

            'devr_email_contact.required' => 'El correo de contacto es obligatorio.',
            'devr_email_contact.email' => 'El correo debe ser un email válido.',
            'devr_email_contact.max' => 'El correo no puede exceder los 255 caracteres.',
            'devr_email_contact.unique' => 'El correo ya existe en el sistema.',

            'devr_phone_contact.required' => 'El teléfono de contacto es obligatorio.',
            'devr_phone_contact.string' => 'El teléfono debe ser un texto válido.',
            'devr_phone_contact.max' => 'El teléfono no puede exceder los 20 caracteres.',

            'devr_website.url' => 'El sitio web debe ser una URL válida.',
            'devr_website.max' => 'El sitio web no puede exceder los 255 caracteres.',

            'ctry_id.required' => 'El país es obligatorio.',
            'ctry_id.exists' => 'El país seleccionado no existe.',

            'user_id.required' => 'El usuario asociado es obligatorio.',
            'user_id.exists' => 'El usuario seleccionado no existe.',

            'devr_active.required' => 'El estado es obligatorio.',
            'devr_active.boolean' => 'El estado debe ser verdadero o falso.',
        ];
    }
}
