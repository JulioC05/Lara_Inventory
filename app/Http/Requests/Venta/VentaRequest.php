<?php

namespace App\Http\Requests\Venta;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class VentaRequest extends FormRequest
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
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [

            'cliente_id' => [
                'nullable',
                'exists:clientes,id'
            ],

            'metodo_pago_id' => [
                'required',
                'exists:metodos_pago,id'
            ],

            'tipo_comprobante' => [
                'required',
                Rule::in(['ticket', 'boleta', 'factura'])
            ],

            'subtotal' => [
                'required',
                'numeric',
                'min:0'
            ],

            'igv' => [
                'required',
                'numeric',
                'min:0'
            ],

            'descuento' => [
                'nullable',
                'numeric',
                'min:0'
            ],

            'total' => [
                'required',
                'numeric',
                'min:0.01'
            ],

            'monto_recibido' => [
                'nullable',
                'numeric',
                'min:0'
            ],

            'vuelto' => [
                'nullable',
                'numeric',
                'min:0'
            ],

            'productos' => [
                'required',
                'array',
                'min:1'
            ],

            'productos.*.id' => [
                'required',
                'exists:productos,id'
            ],

            'productos.*.cantidad' => [
                'required',
                'integer',
                'min:1'
            ],

            'productos.*.precio_unitario' => [
                'required',
                'numeric',
                'min:0'
            ],

        ];
    }

    public function messages(): array
    {
        return [

            'productos.required' =>
            'Debe agregar al menos un producto.',

            'productos.min' =>
            'Debe agregar al menos un producto.',

            'metodo_pago_id.required' =>
            'Seleccione un método de pago.',

            'tipo_comprobante.required' =>
            'Seleccione un tipo de comprobante.',
        ];
    }
}
