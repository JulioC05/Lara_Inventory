<?php

namespace Database\Seeders;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Venta;
use App\Models\Cliente;
use App\Models\Producto;
use App\Models\MetodoPago;
use App\Services\VentaService;
use Illuminate\Database\Seeder;

class VentaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $ventaService = app(VentaService::class);

        $cajero = User::where('email', 'cajero@cajero.com')->first();

        $admin = User::where('email', 'admin@admin.com')->first();

        $clienteVarios = Cliente::where(
            'nombre',
            'Cliente Varios'
        )->first();

        $clientes = Cliente::where(
            'nombre',
            '!=',
            'Cliente Varios'
        )->get();

        $metodosPago = MetodoPago::all();

        $productosAltaRotacion = Producto::whereIn(
            'categoria_id',
            function ($query) {
                $query->select('id')
                    ->from('categorias')
                    ->whereIn('nombre', [
                        'Cervezas',
                        'Energizantes',
                        'Snacks'
                    ]);
            }
        )->get();

        $productosMediaRotacion = Producto::whereIn(
            'categoria_id',
            function ($query) {
                $query->select('id')
                    ->from('categorias')
                    ->whereIn('nombre', [
                        'Ron',
                        'Vodka'
                    ]);
            }
        )->get();

        $productosBajaRotacion = Producto::whereIn(
            'categoria_id',
            function ($query) {
                $query->select('id')
                    ->from('categorias')
                    ->whereIn('nombre', [
                        'Whisky',
                        'Vino Tinto',
                        'Vino Blanco',
                        'Vino Rosado'
                    ]);
            }
        )->get();

        for ($i = 90; $i >= 1; $i--) {

            $fecha = Carbon::now()->subDays($i);

            /*
    |--------------------------------------------------------------------------
    | FINES DE SEMANA = MÁS VENTAS
    |--------------------------------------------------------------------------
    */

            $ventasDia = match ($fecha->dayOfWeek) {

                Carbon::FRIDAY => rand(10, 18),

                Carbon::SATURDAY => rand(15, 25),

                Carbon::SUNDAY => rand(8, 15),

                default => rand(3, 8),
            };

            for ($v = 1; $v <= $ventasDia; $v++) {

                /*
        |--------------------------------------------------------------------------
        | USUARIO
        |--------------------------------------------------------------------------
        */

                $usuario = rand(1, 100) <= 85
                    ? $cajero
                    : $admin;

                /*
        |--------------------------------------------------------------------------
        | CLIENTE
        |--------------------------------------------------------------------------
        */

                $cliente = rand(1, 100) <= 75
                    ? $clienteVarios
                    : $clientes->random();

                /*
        |--------------------------------------------------------------------------
        | METODO PAGO
        |--------------------------------------------------------------------------
        */

                $metodo = $metodosPago->random();

                /*
        |--------------------------------------------------------------------------
        | PRODUCTOS
        |--------------------------------------------------------------------------
        */

                $productosVenta = collect();

                $cantidadProductos = rand(1, 3);

                for ($p = 1; $p <= $cantidadProductos; $p++) {

                    $grupo = rand(1, 100);

                    if ($grupo <= 60) {

                        $producto = $productosAltaRotacion->random();
                    } elseif ($grupo <= 85) {

                        $producto = $productosMediaRotacion->random();
                    } else {

                        $producto = $productosBajaRotacion->random();
                    }

                    /*
            |--------------------------------------------------------------------------
            | EVITAR STOCK 0
            |--------------------------------------------------------------------------
            */

                    $cantidad = rand(1, 2);

                    if ($producto->stock < $cantidad) {
                        continue;
                    }

                    $productosVenta->push([
                        'id' => $producto->id,
                        'cantidad' => $cantidad,
                        'precio_unitario' => $producto->precio_venta,
                    ]);
                }

                if ($productosVenta->isEmpty()) {
                    continue;
                }

                /*
        |--------------------------------------------------------------------------
        | TOTALES
        |--------------------------------------------------------------------------
        */

                $total = $productosVenta->sum(function ($item) {

                    return $item['cantidad']
                        * $item['precio_unitario'];
                });

                $subtotal = $total / 1.18;

                $igv = $total - $subtotal;

                /*
        |--------------------------------------------------------------------------
        | COMPROBANTE
        |--------------------------------------------------------------------------
        */

                $tipoComprobante = rand(1, 100) <= 85
                    ? 'boleta'
                    : 'factura';

                /*
        |--------------------------------------------------------------------------
        | MONTO RECIBIDO
        |--------------------------------------------------------------------------
        */

                $montoRecibido = ceil($total / 10) * 10;

                /*
        |--------------------------------------------------------------------------
        | REGISTRAR VENTA
        |--------------------------------------------------------------------------
        */

                $ventaService->registrar([

                    'cliente_id' => $cliente->id,

                    'metodo_pago_id' => $metodo->id,

                    'tipo_comprobante' => $tipoComprobante,

                    'subtotal' => round($subtotal, 2),

                    'igv' => round($igv, 2),

                    'descuento' => 0,

                    'total' => round($total, 2),

                    'monto_recibido' => $montoRecibido,

                    'vuelto' => round(
                        $montoRecibido - $total,
                        2
                    ),

                    'fecha_venta' => $fecha,

                    'productos' => $productosVenta->toArray(),

                ], $usuario->id);
            }
        }
    }
}
