<?php

namespace App\Http\Requests\Categoria;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateCategoriaRequest extends FormRequest
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

                Rule::unique('categorias', 'nombre')
                    ->ignore($this->categoria)
                    ->whereNull('deleted_at'),
            ]
        ];
    }

    public function messages(): array
    {
        return [
            'nombre.required' => 'El nombre es obligatorio.',
            'nombre.unique' => 'La categoria ya existe.',
            'nombre.max' => 'El nombre no puede superar los 100 caracteres.',
        ];
    }
}
