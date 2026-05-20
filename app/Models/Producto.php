<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Producto extends Model
{
    protected $table = 'productos';

    use SoftDeletes;

    protected $fillable = [
        'categoria_id',
        'marca_id',
        'user_id',
        'nombre',
        'descripcion',
        'contenido_ml',
        'graduacion_alcoholica',
        'codigo_barras',
        'imagen',
        'stock',
        'stock_minimo',
        'precio_compra',
        'margen_ganancia',
        'precio_venta',
        'tipo_afectacion_igv',
        'porcentaje_igv',
        'isc',
        'estado',
    ];

    protected $casts = [
        'estado' => 'boolean',
        'precio_compra' => 'decimal:2',
        'precio_venta' => 'decimal:2',
        'margen_ganancia' => 'decimal:2',
    ];

    public function scopeActivos(Builder $query): Builder
    {
        return $query->where('estado', true);
    }

    public function categoria()
    {
        return $this->belongsTo(Categoria::class);
    }

    public function marca()
    {
        return $this->belongsTo(Marca::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
