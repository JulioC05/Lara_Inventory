<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Categoria extends Model
{
    protected $table = 'categorias';

    use SoftDeletes;

    protected $fillable = [
        'user_id',
        'nombre',
        'descripcion',
        'estado'
    ];

    protected $casts = [
        'estado' => 'boolean',
    ];

     public function scopeActivos(Builder $query): Builder
    {
        return $query->where('estado', true);
    }

     public function user()
    {
        return $this->belongsTo(User::class);
    }
}
