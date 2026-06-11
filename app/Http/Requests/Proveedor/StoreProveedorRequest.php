<?php

namespace App\Http\Requests\Proveedor;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreProveedorRequest extends FormRequest
{

    protected $errorBag = 'store';

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'ruc' => [
                'required',
                'digits:11',
                Rule::unique('proveedores', 'ruc')
                    ->whereNull('deleted_at'),
            ],
            'razon_social' => 'required|string|max:200',
            'contacto_nombre' => 'nullable|string|max:100',
            'telefono' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:100',
            'direccion' => 'nullable|string|max:255',
        ];
    }
}
