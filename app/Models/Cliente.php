<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Cliente extends Model
{
    use SoftDeletes;

    protected $table = 'clientes';

    protected $fillable = [
        'tipo_documento',
        'numero_documento',
        'nombre',
        'apellido',
        'razon_social',
        'telefono',
        'direccion',
        'email',
        'estado',
    ];

    public function esNatural()
    {
        return $this->tipo_persona === 'natural';
    }

    public function esJuridica()
    {
        return $this->tipo_persona === 'juridica';
    }
}
