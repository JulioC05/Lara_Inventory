<?php

namespace App\Http\Requests\Marca;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateMarcaRequest extends FormRequest
{

    protected $errorBag = 'update';

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
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'nombre' => [
                'required',
                'string',
                'max:100',

                Rule::unique('marcas', 'nombre')
                    ->ignore($this->marca)
                    ->whereNull('deleted_at'),
            ]
        ];
    }

    /**
     * Custom validation messages.
     */
    public function messages(): array
    {
        return [
            'nombre.required' => 'El nombre es obligatorio.',
            'nombre.unique' => 'La marca ya existe.',
            'nombre.max' => 'El nombre no puede superar los 100 caracteres.',
        ];
    }
}
