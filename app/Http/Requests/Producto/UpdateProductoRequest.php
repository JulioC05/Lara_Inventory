<?php

namespace App\Http\Requests\Producto;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateProductoRequest extends FormRequest
{

    protected $errorBag = 'update';

    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    // protected function failedValidation(Validator $validator)
    // {
    //     dd($validator->errors()->all());
    // }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $product = $this->route('producto');

        return [
            'categoria_id' => ['required', 'exists:categorias,id'],

            'marca_id' => ['required', 'exists:marcas,id'],

            'nombre' => [
                'required',
                'string',
                'max:100',
                Rule::unique('productos')
                    ->ignore($product->id)
                    ->whereNull('deleted_at'),
            ],

            'descripcion' => [
                'nullable',
                'string',
            ],

            'contenido_ml' => [
                'nullable',
                'integer',
                'min:0',
            ],

            'graduacion_alcoholica' => [
                'nullable',
                'numeric',
                'min:0',
                'max:100',
            ],

            'codigo_barras' => [
                'nullable',
                'string',
                'max:255',
                Rule::unique('productos')
                    ->ignore($product->id)
                    ->whereNull('deleted_at'),
            ],

            'imagen' => [
                'nullable',
                'image',
                'mimes:jpg,jpeg,png,webp',
                'max:2048',
            ],

            'stock' => [
                'required',
                'integer',
                'min:0',
            ],

            'stock_minimo' => [
                'required',
                'integer',
                'min:0',
            ],

            'precio_compra' => [
                'required',
                'numeric',
                'min:0',
            ],

            'precio_venta' => [
                'required',
                'numeric',
                'min:0',
            ],

            // 'tipo_afectacion_igv' => [
            //     'required',
            //     Rule::in([
            //         'gravado',
            //         'exonerado',
            //         'inafecto',
            //     ]),
            // ],

            // 'porcentaje_igv' => [
            //     'required',
            //     'numeric',
            //     'min:0',
            // ],

            // 'isc' => [
            //     'required',
            //     'numeric',
            //     'min:0',
            // ],

            // 'estado' => [
            //     'required',
            //     'boolean',
            // ],
        ];
    }

    public function attributes(): array
    {
        return [
            'categoria_id' => 'categoría',
            'marca_id' => 'marca',
            'contenido_ml' => 'contenido',
            'graduacion_alcoholica' => 'graduación alcohólica',
            'codigo_barras' => 'código de barras',
            'stock_minimo' => 'stock mínimo',
            'precio_compra' => 'precio de compra',
            'precio_venta' => 'precio de venta',
            // 'tipo_afectacion_igv' => 'tipo de afectación IGV',
            // 'porcentaje_igv' => 'porcentaje IGV',
        ];
    }
}
