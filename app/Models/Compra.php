<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
// use Illuminate\Database\Eloquent\Factories\HasFactory;

class Compra extends Model
{
    use SoftDeletes;

    protected $table = 'compras';

    protected $fillable = [
        'proveedor_id',
        'user_id',
        'metodo_pago_id',
        'numero_comprobante',
        'subtotal',
        'igv',
        'total',
        'fecha_pedido',
        'fecha_entrega',
        'observaciones',
        'estado'
    ];

    public function proveedor()
    {
        return $this->belongsTo(Proveedor::class);
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
        return $this->hasMany(DetalleCompra::class);
    }
}
