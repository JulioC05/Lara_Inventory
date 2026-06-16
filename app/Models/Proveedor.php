<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Proveedor extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'proveedores';

    protected $fillable = [
        'user_id',
        'ruc',
        'razon_social',
        'contacto_nombre',
        'telefono',
        'email',
        'direccion',
        'estado'
    ];

    public function compras()
    {
        return $this->hasMany(Compra::class);
    }
}
