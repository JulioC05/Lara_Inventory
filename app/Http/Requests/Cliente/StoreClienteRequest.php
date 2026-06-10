<?php

namespace App\Http\Requests\Cliente;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreClienteRequest extends FormRequest
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
                Rule::unique('clientes')->where(function ($query) {
                    return $query->where('tipo_documento', $this->tipo_documento)->whereNull('deleted_at');
                }),
            ],

            // Persona natural
            'nombre' => [
                'required_if:tipo_persona,natural|nullable|string|max:100'
            ],

            'apellido' => [
                'required_if:tipo_persona,natural|nullable|string|max:100'
            ],

            // Empresa
            'razon_social' => [
                'required_if:tipo_persona,juridica|nullable|string|max:200'
            ],

            'nombre_comercial' => 'nullable|string|max:255',

            'contacto_nombre'  => 'nullable|string|max:255',

            'contacto_cargo'  => 'nullable|string|max:255',

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

            //VALIDACIONES POR TIPO DE DOCUMENTO

            $tipoDocumento =
                $this->tipo_documento;

            $numeroDocumento =
                $this->numero_documento;

            // =====================================
            // DNI
            // =====================================

            if (
                $tipoDocumento === 'DNI' &&
                $numeroDocumento &&
                strlen($numeroDocumento) != 8
            ) {

                $validator->errors()->add(
                    'numero_documento',
                    'El DNI debe tener 8 dígitos.'
                );
            }

            // =====================================
            // RUC
            // =====================================

            if (
                $tipoDocumento === 'RUC' &&
                $numeroDocumento &&
                strlen($numeroDocumento) != 11
            ) {

                $validator->errors()->add(
                    'numero_documento',
                    'El RUC debe tener 11 dígitos.'
                );
            }
        });
    }
}
