<?php

namespace App\Http\Requests\Cliente;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateClienteRequest extends FormRequest
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
            'tipo_persona' => [
                'required',
                Rule::in(['natural', 'juridica'])
            ],

            'tipo_documento' => [
                'nullable',
                'string',
                'max:20',
            ],

            'numero_documento' => [
                'nullable',
                'max:20',
                Rule::unique('clientes', 'numero_documento')
                    ->ignore($this->cliente)
                    ->whereNull('deleted_at')
            ],

            // Persona natural
            'nombre' => [
                'nullable',
                'max:100'
            ],

            'apellido' => [
                'nullable',
                'max:100'
            ],

            // Empresa
            'razon_social' => [
                'nullable',
                'max:150'
            ],

            'telefono' => [
                'nullable',
                'max:20'
            ],

            'direccion' => [
                'nullable',
                'max:255'
            ],

            'email' => [
                'nullable',
                'email',
                'max:100'
            ],

            // 'estado' => [
            //     'nullable',
            //     'boolean'
            // ]
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {

            // NATURAL
            if ($this->tipo_persona === 'natural') {

                if (!$this->nombre) {

                    $validator->errors()->add(
                        'nombre',
                        'El nombre es obligatorio.'
                    );
                }

                if (
                    !$this->tipo_documento ||
                    !in_array($this->tipo_documento, [
                        'DNI',
                        'CE',
                        'PASAPORTE'
                    ])
                ) {

                    $validator->errors()->add(
                        'tipo_documento',
                        'Documento inválido para persona natural.'
                    );
                }
            }

            // JURIDICA
            if ($this->tipo_persona === 'juridica') {

                if (!$this->razon_social) {

                    $validator->errors()->add(
                        'razon_social',
                        'La razón social es obligatoria.'
                    );
                }

                if ($this->tipo_documento !== 'RUC') {

                    $validator->errors()->add(
                        'tipo_documento',
                        'Las empresas solo pueden usar RUC.'
                    );
                }
            }
        });
    }
}
