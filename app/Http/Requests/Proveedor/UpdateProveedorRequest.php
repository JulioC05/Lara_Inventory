<?php

namespace App\Http\Requests\Proveedor;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateProveedorRequest extends FormRequest
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
            'ruc' => [
                'required',
                'digits:11',
                Rule::unique('proveedores', 'ruc')
                    ->ignore($proveedor->id)
                    ->whereNull('deleted_at'),
            ],

            'razon_social' => [
                'required',
                'string',
                'max:200',
            ],

            'contacto_nombre' => [
                'nullable',
                'string',
                'max:100',
            ],

            'telefono' => [
                'nullable',
                'string',
                'max:20',
            ],

            'email' => [
                'nullable',
                'email',
                'max:200',
            ],

            'direccion' => [
                'nullable',
                'string',
                'max:255',
            ]
        ];
    }
}
