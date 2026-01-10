<?php
// filepath: app/Http/Requests/UserRequest.php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class UserRequest extends FormRequest
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
        $userId = $this->route('user');
        $isUpdate = $this->method() === 'PUT' || $this->method() === 'PATCH';

        return [
            'name' => $isUpdate
                ? ['nullable', 'string', 'max:255']
                : ['required', 'string', 'max:255'],
            'lastname' => $isUpdate
                ? ['nullable', 'string', 'max:255']
                : ['required', 'string', 'max:255'],
            'email' => $isUpdate
                ? ['nullable', 'email', 'max:255', Rule::unique('users', 'email')->ignore($userId, 'id')]
                : ['required', 'email', 'max:255', Rule::unique('users', 'email')],
            'phone' => 'nullable|string|max:20',
            'idiomas' => 'nullable|string|in:Español,Inglés,Francés',
            'profile_photo_path' => 'nullable|file|mimes:jpeg,jpg,png,gif|max:5242880',
            'password' => $isUpdate
                ? ['nullable', Password::defaults(), 'confirmed']
                : ['required', Password::defaults(), 'confirmed'],
            'password_confirmation' => $isUpdate
                ? ['nullable', 'required_with:password']
                : ['required'],
            'usr_id_ctry' => ['nullable', 'exists:countries,ctry_id'],
            'usr_active' => ['nullable', 'boolean'],
            'roles' => ['nullable', 'array'],
            'roles.*' => ['exists:roles,id'],
            'permissions' => ['nullable', 'array'],
            'permissions.*' => ['exists:permissions,id'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'El nombre es obligatorio.',
            'name.string' => 'El nombre debe ser un texto válido.',
            'name.max' => 'El nombre no puede exceder los 255 caracteres.',

            'lastname.required' => 'El apellido es obligatorio.',
            'lastname.string' => 'El apellido debe ser un texto válido.',
            'lastname.max' => 'El apellido no puede exceder los 255 caracteres.',

            'email.required' => 'El correo electrónico es obligatorio.',
            'email.email' => 'El correo electrónico debe ser válido.',
            'email.unique' => 'El correo electrónico ya existe.',
            'email.max' => 'El correo electrónico no puede exceder los 255 caracteres.',

            'phone.string' => 'El teléfono debe ser un texto válido.',
            'phone.max' => 'El teléfono no puede exceder los 20 caracteres.',

            'idiomas.string' => 'Los idiomas deben ser un texto válido.',
            'idiomas.max' => 'Los idiomas no pueden exceder los 500 caracteres.',

            'profile_photo_path.file' => 'La foto debe ser un archivo válido.',
            'profile_photo_path.mimes' => 'La foto debe ser JPG, PNG o GIF.',
            'profile_photo_path.max' => 'La foto no puede exceder los 5MB.',

            'password.required' => 'La contraseña es obligatoria.',
            'password.min' => 'La contraseña debe tener al menos 8 caracteres.',

            'usr_id_ctry.exists' => 'El país seleccionado no existe.',

            'usr_active.boolean' => 'El estado debe ser verdadero o falso.',

            'roles.array' => 'Los roles deben ser un array.',
            'roles.*.exists' => 'Uno de los roles seleccionados no existe.',
            
            'permissions.array' => 'Los permisos deben ser un array.',
            'permissions.*.exists' => 'Uno de los permisos seleccionados no existe.',
        ];
    }
}