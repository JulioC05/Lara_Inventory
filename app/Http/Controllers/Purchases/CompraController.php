<?php

namespace App\Http\Controllers\Purchases;

use App\Http\Controllers\Controller;
use App\Models\Compra;
use App\Models\DetalleCompra;
use App\Models\MetodoPago;
use App\Models\MovimientoStock;
use App\Models\Producto;
use App\Models\Proveedor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CompraController extends Controller
{
    public function index()
    {
        $titulo = "Administrar Compras";
        // Cargamos las relaciones con 'with' (Eager Loading) para evitar el problema de consultas N+1 (Seguridad de rendimiento)
        $compras = Compra::with(['proveedor', 'usuario'])->latest()->get();

        return view('modules.compras.index', compact('compras', 'titulo'));
    }

    public function create()
    {
        $titulo = "Registrar Nueva Compra";

        $proveedores = Proveedor::where('estado', true)->latest()->get();
        $productos = Producto::where('estado', true)->latest()->get();
        $metodosPago = MetodoPago::where('estado', true)->latest()->get();

        return view('modules.compras.create', compact('proveedores', 'productos', 'metodosPago', 'titulo'));
    }

    public function show($id)
    {
        $titulo = "Detalle de Compra";

        // CARGA SEGURA (Eager Loading): 
        // Cargamos la compra junto con el proveedor, el usuario que registró, 
        // el método de pago y los productos dentro de cada detalle.
        // Esto evita que Laravel haga consultas individuales por cada fila en el Blade (Problema N+1).
        $compra = Compra::with([
            'proveedor',
            'usuario',
            'metodoPago',
            'detalles.producto'
        ])->findOrFail($id);

        return view('modules.compras.show', compact('compra', 'titulo'));
    }

    public function store(Request $request)
    {
        // 1. Validación estricta del Request
        $request->validate([
            'proveedor_id'       => 'required|exists:proveedores,id',
            'metodo_pago_id'     => 'required|exists:metodos_pago,id',
            'numero_comprobante' => 'nullable|string|max:50',
            'fecha_pedido'       => 'required|date',
            'fecha_entrega'      => 'nullable|date|after_or_equal:fecha_pedido',
            'observaciones'      => 'nullable|string',
            'estado'             => 'required|in:pendiente,recibida',
            // Validación de los arrays dinámicos que enviará el formulario
            'productos'          => 'required|array|min:1',
            'productos.*'        => 'exists:productos,id',
            'cantidades'         => 'required|array',
            'cantidades.*'       => 'required|integer|min:1',
            'precios'            => 'required|array',
            'precios.*'          => 'required|numeric|min:0',
        ]);

        // 2. Iniciar Transacción de Base de Datos para asegurar la integridad
        DB::beginTransaction();

        try {
            // Inicializamos acumuladores financieros para recalcular en el servidor 
            // (NUNCA confíes en los totales que envía el cliente/navegador por seguridad)
            $subtotalGeneral = 0;
            $igvGeneral = 0;
            $totalGeneral = 0;

            // Pre-calculamos todo recorriendo los arrays
            $detallesPreparados = [];

            foreach ($request->productos as $index => $productoId) {
                $cantidad = $request->cantidades[$index];
                $precioUnitario = $request->precios[$index];

                // Buscamos el producto para conocer su afectación de IGV real en tu base de datos
                $producto = Producto::findOrFail($productoId);

                // Lógica de cálculo según tu campo 'tipo_afectacion_igv' de productos
                $subtotalFila = $cantidad * $precioUnitario;
                $igvFila = 0;

                if ($producto->tipo_afectacion_igv === 'gravado') {
                    // Si el precio unitario ya incluye IGV (lo común en compras):
                    $porcentajeIgv = $producto->porcentaje_igv / 100;
                    $subtotalFila = ($cantidad * $precioUnitario) / (1 + $porcentajeIgv);
                    $igvFila = ($cantidad * $precioUnitario) - $subtotalFila;
                }

                $totalFila = $subtotalFila + $igvFila;

                // Acumulamos a los totales de la cabecera
                $subtotalGeneral += $subtotalFila;
                $igvGeneral += $igvFila;
                $totalGeneral += $totalFila;

                // Guardamos en memoria el detalle listo para insertar
                $detallesPreparados[] = [
                    'producto_id'            => $productoId,
                    'cantidad'               => $cantidad,
                    'precio_unitario_compra' => $precioUnitario,
                    'igv'                    => $igvFila,
                    'subtotal'               => $subtotalFila,
                    'total'                  => $totalFila,
                ];
            }

            // 3. Crear la Cabecera de la Compra
            $compra = Compra::create([
                'proveedor_id'       => $request->proveedor_id,
                'user_id'            => auth()->id(), // Auditoría
                'metodo_pago_id'     => $request->metodo_pago_id,
                'numero_comprobante' => $request->numero_comprobante,
                'fecha_pedido'       => $request->fecha_pedido,
                'fecha_entrega'      => $request->fecha_entrega,
                'observaciones'      => $request->observaciones,
                'subtotal'           => round($subtotalGeneral, 2),
                'igv'                => round($igvGeneral, 2),
                'total'              => round($totalGeneral, 2),
                'estado'             => $request->estado,
            ]);

            // 4. Registrar los detalles y aumentar stock SI la compra se marca como 'recibida'
            foreach ($detallesPreparados as $detalle) {
                // Añadimos el ID de la cabecera recién creada
                $detalle['compra_id'] = $compra->id;
                DetalleCompra::create($detalle);

                // CONTROL DE STOCK LOGÍSTICO SEGURO:
                // Si la compra entra directamente como 'recibida', el producto ya está en el almacén
                if ($request->estado === 'recibida') {
                    $producto = Producto::find($detalle['producto_id']);

                    $stockAnterior = $producto->stock;
                    $producto->increment('stock', $detalle['cantidad']);
                    $stockNuevo = $producto->stock;

                    $producto->update(['precio_compra' => $detalle['precio_unitario_compra']]);

                    // REGISTRO EN TU KARDEX
                    MovimientoStock::create([
                        'producto_id'     => $producto->id,
                        'user_id'         => auth()->id(),
                        'tipo_movimiento' => 'entrada',
                        'motivo'          => 'compra',
                        'referencia'      => $request->numero_comprobante ?? "Compra #{$compra->id}",
                        'cantidad'        => $detalle['cantidad'], // Positivo en BD
                        'stock_anterior'  => $stockAnterior,
                        'stock_nuevo'     => $stockNuevo,
                    ]);
                }
            }

            // Si todo salió bien, guardamos definitivamente en la base de datos
            DB::commit();

            return to_route('compras.index')
                ->with('success', 'Compra registrada correctamente' . ($request->estado === 'recibida' ? ' e inventario actualizado.' : '.'));
        } catch (\Exception $e) {
            // Si algo falló (Base de datos caída, error de código, etc.), deshacemos todo
            DB::rollBack();

            return back()
                ->withInput() // Mantiene los datos del formulario para que el usuario no los pierda
                ->with('error', 'Error crítico al procesar la compra. El inventario no fue alterado.');
        }
    }

    /**
     * Procesa la recepción física de una compra pendiente y carga el stock.
     */
    public function recibir(Request $request, $id)
    {
        // 1. Validación estricta de los datos del modal
        $request->validate([
            'numero_comprobante' => 'required|string|max:50',
            'fecha_entrega'      => 'required|date',
            'observaciones'      => 'nullable|string'
        ]);

        // 2. Iniciar Transacción de Base de Datos
        DB::beginTransaction();

        try {
            // Buscamos la compra cargando sus detalles (Eager Loading)
            $compra = Compra::with('detalles.producto')->findOrFail($id);

            // CONTROL DE SEGURIDAD INTERNA: 
            // Si la compra ya no está pendiente (ej. ya fue recibida o anulada), abortamos de inmediato.
            if ($compra->estado !== 'pendiente') {
                return to_route('compras.index')
                    ->with('error', 'Esta compra ya ha sido procesada o se encuentra anulada.');
            }

            // 3. Actualizar la cabecera con los datos reales del proveedor
            $compra->update([
                'numero_comprobante' => $request->numero_comprobante,
                'fecha_entrega'      => $request->fecha_entrega,
                'observaciones'      => $request->observaciones,
                'estado'             => 'recibida' // Cambia el flujo a completado
            ]);

            // 4. Recorrer los detalles congelados para inyectar el stock físico al almacén
            foreach ($compra->detalles as $detalle) {
                $producto = $detalle->producto;

                if ($producto) {
                    $stockAnterior = $producto->stock;
                    $producto->increment('stock', $detalle['cantidad']);
                    $stockNuevo = $producto->stock;

                    $producto->update([
                        'precio_compra' => $detalle['precio_unitario_compra']
                    ]);

                    // REGISTRO EN TU KARDEX
                    MovimientoStock::create([
                        'producto_id'     => $producto->id,
                        'user_id'         => auth()->id(),
                        'tipo_movimiento' => 'entrada',
                        'motivo'          => 'compra',
                        'referencia'      => $compra->numero_comprobante, // Ya validado del modal
                        'cantidad'        => $detalle['cantidad'],
                        'stock_anterior'  => $stockAnterior,
                        'stock_nuevo'     => $stockNuevo,
                    ]);
                }
            }

            // Si todo el bucle corrió sin errores, guardamos en la base de datos de forma definitiva
            DB::commit();

            return to_route('compras.index')
                ->with('success', 'Mercadería recibida correctamente. El stock ha sido actualizado en el almacén.');
        } catch (\Exception $e) {
            // En caso de cualquier caída del servidor o error, revertimos todo al estado anterior
            DB::rollBack();

            return to_route('compras.index')
                ->with('error', 'Error crítico al procesar el ingreso de mercadería. Intente nuevamente.');
        }
    }

    /**
     * Anula una compra de forma lógica y revierte el stock si ya fue recibida.
     */
    public function anular($id)
    {
        DB::beginTransaction();

        try {
            $compra = Compra::with('detalles.producto')->findOrFail($id);

            if ($compra->estado === 'anulada') {
                return to_route('compras.index')->with('error', 'Esta compra ya se encuentra anulada.');
            }

            // Si la compra YA había ingresado al almacén, debemos retirar esos licores del stock
            if ($compra->estado === 'recibida') {
                foreach ($compra->detalles as $detalle) {
                    $producto = $detalle->producto;

                    if (($producto->stock - $detalle->cantidad) < 0) {
                        DB::rollBack();
                        return to_route('compras.index')
                            ->with('error', "No puedes anular esta compra. El stock de {$producto->nombre} quedaría en negativo.");
                    }

                    $stockAnterior = $producto->stock;
                    $producto->decrement('stock', $detalle['cantidad']);
                    $stockNuevo = $producto->stock;

                    // REGISTRO EN TU KARDEX (SALIDA POR ANULACIÓN)
                    MovimientoStock::create([
                        'producto_id'     => $producto->id,
                        'user_id'         => auth()->id(),
                        'tipo_movimiento' => 'salida',
                        'motivo'          => 'anulacion_compra',
                        'referencia'      => $compra->numero_comprobante ?? "Anulación #{$compra->id}",
                        'cantidad'        => $detalle['cantidad'], // En tu reporte lo multiplicas por -1
                        'stock_anterior'  => $stockAnterior,
                        'stock_nuevo'     => $stockNuevo,
                    ]);
                }
            }

            // Cambiamos el estado de la cabecera de forma segura
            $compra->update(['estado' => 'anulada']);

            DB::commit();
            return to_route('compras.index')->with('success', 'Compra anulada correctamente. El inventario ha sido recalculado.');
        } catch (\Exception $e) {
            DB::rollBack();
            return to_route('compras.index')->with('error', 'No se pudo procesar la anulación de la compra.');
        }
    }
}
