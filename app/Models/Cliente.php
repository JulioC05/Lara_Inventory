<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Cliente extends Model
{
    use SoftDeletes;

    protected $table = 'clientes';

    protected $fillable = [
        'user_id',
        'tipo_persona',
        'tipo_documento',
        'numero_documento',
        'nombre',
        'apellido',
        'razon_social',
        'contacto_nombre',
        'contacto_cargo',
        'telefono',
        'direccion',
        'email',
        'estado',
    ];

    public function ventas()
    {
        return $this->hasMany(Venta::class);
    }

    public function esNatural()
    {
        return $this->tipo_persona === 'natural';
    }

    public function esJuridica()
    {
        return $this->tipo_persona === 'juridica';
    }

    public function getNombreCompletoAttribute()
    {
        if ($this->tipo_persona === 'juridica') {

            return $this->razon_social;
        }

        return trim(
            "{$this->nombre} {$this->apellido}"
        );
    }
}
