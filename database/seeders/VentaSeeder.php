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

        // 1. Clasificación inteligente de clientes para cumplir las reglas SUNAT
        $clienteVarios = Cliente::where('tipo_documento', 'DNI')
            ->where('numero_documento', '00000000')
            ->first();

        // Clientes con RUC (Aptos para factura o boletas altas)
        $clientesConRuc = Cliente::where('tipo_documento', 'RUC')->get();

        // Clientes con DNI identificados (Aptos para cualquier boleta)
        $clientesConDni = Cliente::where('tipo_documento', 'DNI')
            ->where('numero_documento', '!=', '00000000')
            ->get();

        $metodosPago = MetodoPago::all();

        // Grupos de productos (Se mantienen igual)
        $productosAltaRotacion = Producto::whereIn('categoria_id', function ($query) {
            $query->select('id')->from('categorias')->whereIn('nombre', ['Cervezas', 'Energizantes', 'Snacks']);
        })->get();

        $productosMediaRotacion = Producto::whereIn('categoria_id', function ($query) {
            $query->select('id')->from('categorias')->whereIn('nombre', ['Ron', 'Vodka']);
        })->get();

        $productosBajaRotacion = Producto::whereIn('categoria_id', function ($query) {
            $query->select('id')->from('categorias')->whereIn('nombre', ['Whisky', 'Vino Tinto', 'Vino Blanco', 'Vino Rosado']);
        })->get();

        // Bucle de los últimos 90 días hacia atrás
        for ($i = 90; $i >= 1; $i--) {
            $fecha = Carbon::now()->subDays($i);

            $ventasDia = match ($fecha->dayOfWeek) {
                Carbon::FRIDAY => rand(10, 18),
                Carbon::SATURDAY => rand(15, 25),
                Carbon::SUNDAY => rand(8, 15),
                default => rand(3, 8),
            };

            for ($v = 1; $v <= $ventasDia; $v++) {

                $usuario = rand(1, 100) <= 85 ? $cajero : $admin;
                $metodo = $metodosPago->random();

                /*
                |--------------------------------------------------------------------------
                | GENERACIÓN DE PRODUCTOS DE LA VENTA
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

                    // Forzamos compras moderadas por ítem para regular los totales azarosos
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

                // Cálculo preventivo del monto total de la venta actual
                $total = $productosVenta->sum(function ($item) {
                    return $item['cantidad'] * $item['precio_unitario'];
                });

                /*
                |--------------------------------------------------------------------------
                | POLÍTICA SUNAT PARA SEEDER: DETERMINACIÓN DEL COMPROBANTE Y CLIENTE
                |--------------------------------------------------------------------------
                */
                $probabilidadComprobante = rand(1, 100);

                if ($probabilidadComprobante <= 85) {
                    // 🧾 ES BOLETA
                    $tipoComprobante = 'boleta';

                    if ($total >= 700.00) {
                        // Si por azar el monto total es >= S/ 700, SUNAT exige DNI/RUC identificado.
                        // Tomamos un cliente real con DNI o uno con RUC (ambos son válidos para boletas identificadas)
                        $cliente = rand(1, 2) === 1 ? $clientesConDni->random() : $clientesConRuc->random();
                    } else {
                        // Si es menor a S/ 700, el 80% de las veces es anónimo ("Cliente Varios")
                        $cliente = rand(1, 100) <= 80 ? $clienteVarios : $clientesConDni->random();
                    }
                } else {
                    // 🏢 ES FACTURA
                    $tipoComprobante = 'factura';

                    // SUNAT obliga a que tenga RUC (Sea RUC 10 o RUC 20)
                    $cliente = $clientesConRuc->random();
                }

                /*
                |--------------------------------------------------------------------------
                | TOTALES FINALES
                |--------------------------------------------------------------------------
                */
                $subtotal = $total / 1.18;
                $igv = $total - $subtotal;
                // $montoRecibido = ceil($total / 10) * 10;

                /*
                |--------------------------------------------------------------------------
                | REGISTRAR VENTA MEDIANTE EL SERVICE
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
                    // 'monto_recibido' => $montoRecibido,
                    // 'vuelto' => round($montoRecibido - $total, 2),
                    'fecha_venta' => $fecha,
                    'productos' => $productosVenta->toArray(),
                ], $usuario->id);
            }
        }
    }
}
