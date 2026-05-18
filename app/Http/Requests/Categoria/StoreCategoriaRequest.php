<?php

namespace App\Http\Requests\Categoria;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreCategoriaRequest extends FormRequest
{

    protected $errorBag = 'store';

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
                Rule::unique('categorias')
                    ->whereNull('deleted_at'),
            ]
        ];
    }

    public function messages(): array
    {
        return [
            'nombre.required' => 'El nombre es obligatorio.',
            'nombre.unique' => 'La categoria ya existe.',
            'nombre.max' => 'Máximo 100 caracteres.'
        ];
    }
}
