<?php

namespace App\Services;

use App\Models\Venta;
use App\Models\Producto;
use Illuminate\Support\Facades\DB;
use App\Models\MovimientoStock;

class VentaService
{
    public function registrar(array $data, int $userId): Venta
    {
        return DB::transaction(function () use ($data, $userId) {

            /*
            |--------------------------------------------------------------------------
            | CALCULO N° COMPROBANTE
            |--------------------------------------------------------------------------
            */

            $tipo = $data['tipo_comprobante'];

            $serie = match ($tipo) {
                'boleta' => 'B001',
                'factura' => 'F001',
            };

            $ultimaVenta = Venta::where(
                'tipo_comprobante',
                $tipo
            )
                ->whereNotNull('numero_comprobante')
                ->latest('id')
                ->first();

            $ultimoNumero = 0;

            if ($ultimaVenta) {

                $partes = explode(
                    '-',
                    $ultimaVenta->numero_comprobante
                );

                $ultimoNumero = (int) $partes[1];
            }

            $nuevoNumero = str_pad(
                $ultimoNumero + 1,
                8,
                '0',
                STR_PAD_LEFT
            );

            $numeroComprobante = "{$serie}-{$nuevoNumero}";

            /*
            |--------------------------------------------------------------------------
            | CREAR VENTA
            |--------------------------------------------------------------------------
            */

            $venta = Venta::create([

                'cliente_id' => $data['cliente_id'],
                'user_id' => $userId,
                'metodo_pago_id' => $data['metodo_pago_id'],
                'numero_comprobante' => $numeroComprobante,
                'tipo_comprobante' => $data['tipo_comprobante'],
                'subtotal' => $data['subtotal'],
                'igv' => $data['igv'],
                'descuento' => $data['descuento'] ?? 0,
                'total' => $data['total'],
                'monto_recibido' => $data['monto_recibido'] ?? null,
                'vuelto' => $data['vuelto'] ?? 0,
                'estado' => 'completada',
                'fecha_venta' => $data['fecha_venta'] ?? now(),
            ]);

            /*
            |--------------------------------------------------------------------------
            | DETALLE
            |--------------------------------------------------------------------------
            */

            foreach ($data['productos'] as $item) {

                $producto = Producto::findOrFail($item['id']);

                /*
                |--------------------------------------------------------------------------
                | VALIDAR STOCK
                |--------------------------------------------------------------------------
                */

                if ($producto->stock < $item['cantidad']) {

                    throw new \Exception(
                        "Stock insuficiente para {$producto->nombre}"
                    );
                }

                /*
                |--------------------------------------------------------------------------
                | CALCULOS
                |--------------------------------------------------------------------------
                */

                $total = $item['precio_unitario'] * $item['cantidad'];

                $subtotal = $total / 1.18;

                $igv = $total - $subtotal;

                /*
                |--------------------------------------------------------------------------
                | DETALLE VENTA
                |--------------------------------------------------------------------------
                */

                $venta->detalles()->create([

                    'producto_id' => $producto->id,

                    'cantidad' => $item['cantidad'],

                    'precio_unitario' => $item['precio_unitario'],

                    'igv' => $igv,

                    'descuento' => 0,

                    'subtotal' => $subtotal,

                    'total' => $total,
                ]);

                /*
                |--------------------------------------------------------------------------
                | DESCONTAR STOCK
                |--------------------------------------------------------------------------
                */

                $stockAnterior = $producto->stock;

                $producto->decrement(
                    'stock',
                    $item['cantidad']
                );

                $stockNuevo = $producto->fresh()->stock;

                /*
                |--------------------------------------------------------------------------
                | MOVIMIENTO STOCK
                |--------------------------------------------------------------------------
                */

                MovimientoStock::create([

                    'producto_id' => $producto->id,

                    'user_id' => $userId,

                    'tipo_movimiento' => 'salida',

                    'motivo' => 'venta',

                    'referencia' => $venta->numero_comprobante,

                    'cantidad' => $item['cantidad'],

                    'stock_anterior' => $stockAnterior,

                    'stock_nuevo' => $stockNuevo,
                ]);
            }

            return $venta;
        });
    }
}
