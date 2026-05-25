<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Venta extends Model
{
    use SoftDeletes;

    protected $table = 'ventas';

    protected $fillable = [
        'cliente_id',
        'user_id',
        'metodo_pago_id',
        'numero_comprobante',
        'tipo_comprobante',
        'subtotal',
        'igv',
        'descuento',
        'total',
        'monto_recibido',
        'vuelto',
        'estado',
        'fecha_venta',
    ];

    public function cliente()
    {
        return $this->belongsTo(Cliente::class);
    }

    public function usuario()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function metodoPago()
    {
        return $this->belongsTo(MetodoPago::class);
    }

    public function detalles()
    {
        return $this->hasMany(DetalleVenta::class);
    }
}
