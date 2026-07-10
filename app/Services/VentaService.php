<?php

namespace App\Services;

use App\Models\Venta;
use App\Models\Producto;
use Illuminate\Support\Facades\DB;
use App\Models\MovimientoStock;
use App\Models\User;
use App\Notifications\LowStockNotification;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;

class VentaService
{
    public function registrar(array $data, int $userId): Venta
    {

        /*
        |--------------------------------------------------------------------------
        | VALIDACIONES BAJO NORMATIVA SUNAT (PERÚ)
        |--------------------------------------------------------------------------
        */
        $tipoComprobante = $data['tipo_comprobante'];
        $montoTotal = $data['total'];
        $clienteId = $data['cliente_id'] ?? null;

        // Buscamos al cliente si es que se ha enviado un ID
        $cliente = $clienteId ? \App\Models\Cliente::find($clienteId) : null;

        // RULE 1: Validación para FACTURAS
        if ($tipoComprobante === 'factura') {
            if (!$cliente) {
                throw new \InvalidArgumentException(
                    "Para emitir una Factura es obligatorio registrar e identificar a un Cliente con RUC."
                );
            }
            if ($cliente->tipo_documento !== 'RUC' || strlen($cliente->numero_documento) !== 11) {
                throw new \InvalidArgumentException(
                    "El cliente seleccionado no cuenta con un RUC válido de 11 dígitos requerido para Facturas."
                );
            }
        }

        // RULE 2: Validación para BOLETAS (Monto >= S/ 700.00)
        if ($tipoComprobante === 'boleta' && $montoTotal >= 700.00) {
            // Suponiendo que tu cliente genérico "Clientes Varios" tiene ID 1 o no tiene documento
            if (!$cliente || empty($cliente->numero_documento) || $cliente->id == 1) {
                throw new \InvalidArgumentException(
                    "Las boletas con montos mayores o iguales a S/ 700.00 exigen identificar obligatoriamente al cliente con su DNI/CE."
                );
            }
        }

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

                $productoActualizado = $producto->fresh();
                $stockNuevo = $productoActualizado->stock;

                if ($stockAnterior > $productoActualizado->stock_minimo && $stockNuevo <= $productoActualizado->stock_minimo) {
                    try {
                        $administradores = User::role('admin')->get();

                        if ($administradores->isNotEmpty()) {
                            Notification::send(
                                $administradores,
                                new LowStockNotification($productoActualizado)
                            );
                        }
                    } catch (\Exception $e) {
                        Log::error("Error enviando notificación de stock: " . $e->getMessage());
                    }
                }

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
