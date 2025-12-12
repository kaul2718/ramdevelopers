<?php
namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ApprovalStatusRequest extends FormRequest
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
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $id = $this->route('approvalstatus'); // <-- aquí el fix

        return [
            'apvSta_name'        => [
                'required',
                'string',
                'max:100',
                Rule::unique('approval_statuses', 'apvSta_name')
                    ->ignore($id, 'apvSta_id'),
            ],
            'apvSta_code'        => [
                'required',
                'string',
                'max:50',
                Rule::unique('approval_statuses', 'apvSta_code')
                    ->ignore($id, 'apvSta_id'),
            ],
            'apvSta_description' => 'nullable|string',
            'apvSta_active'      => ['required', 'boolean'],

        ];
    }

    public function messages(): array
    {
        return [
            // NOMBRE
            'apvSta_name.required'      => 'El nombre del estado es obligatorio.',
            'apvSta_name.string'        => 'El nombre debe ser un texto válido.',
            'apvSta_name.max'           => 'El nombre no puede exceder los 100 caracteres.',
            'apvSta_name.unique'        => 'El estado ya existe.',

            // CÓDIGO
            'apvSta_code.required'      => 'El código es obligatorio.',
            'apvSta_code.string'        => 'El código debe ser un texto válido.',
            'apvSta_code.max'           => 'El código no puede exceder los 50 caracteres.',
            'apvSta_code.unique'        => 'El código ya existe.',

            // DESCRIPCIÓN
            'apvSta_description.string' => 'La descripción debe ser un texto.',
        ];
    }

}
