<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MovimientoStock extends Model
{
    protected $table = 'movimientos_stock';

    protected $fillable = [

        'producto_id',

        'user_id',

        'tipo_movimiento',

        'cantidad',

        'stock_anterior',

        'stock_nuevo',

        'motivo',

        'referencia',

    ];
}
