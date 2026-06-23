<?php

namespace App\Http\Controllers\Reports;

use App\Http\Controllers\Controller;
use App\Models\Compra;
use App\Models\DetalleCompra;
use App\Models\MovimientoStock;
use App\Models\Venta;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    public function ventas(Request $request)
    {
        $titulo = 'Reporte Ventas';
        $query = Venta::with([
            'cliente',
            'metodoPago',
            'usuario'
        ]);

        if ($request->filled('fecha_inicio')) {

            $query->whereDate(
                'fecha_venta',
                '>=',
                $request->fecha_inicio
            );
        }

        if ($request->filled('fecha_fin')) {

            $query->whereDate(
                'fecha_venta',
                '<=',
                $request->fecha_fin
            );
        }

        if ($request->filled('estado')) {

            $query->where(
                'estado',
                $request->estado
            );
        }

        if ($request->filled('tipo_comprobante')) {

            $query->where(
                'tipo_comprobante',
                $request->tipo_comprobante
            );
        }

        $ventas = $query
            ->latest('fecha_venta')
            ->paginate(10)
            ->withQueryString();

        $totalVentas = $query
            ->where('estado', 'completada')
            ->sum('total');

        $cantidadVentas = $ventas->total();

        return view(
            'modules.reports.ventas',
            compact(
                'titulo',
                'ventas',
                'totalVentas',
                'cantidadVentas'
            )
        );
    }

    public function movimientosStock(Request $request)
    {
        $titulo = 'Movimientos Stocks';
        $query = MovimientoStock::with(
            'producto'
        );

        /*
    |--------------------------------------------------------------------------
    | FILTRO TIPO
    |--------------------------------------------------------------------------
    */

        if ($request->filled('tipo_movimiento')) {

            $query->where(
                'tipo_movimiento',
                $request->tipo
            );
        }

        /*
    |--------------------------------------------------------------------------
    | FILTRO MOTIVO
    |--------------------------------------------------------------------------
    */

        if ($request->filled('motivo')) {

            $query->where(
                'motivo',
                $request->motivo
            );
        }

        /*
    |--------------------------------------------------------------------------
    | DATOS
    |--------------------------------------------------------------------------
    */

        $movimientos = $query
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view(
            'modules.reports.movimientos-stock',
            compact('movimientos', 'titulo')
        );
    }

    public function compras(Request $request)
    {
        $titulo = 'Reporte Compras';
        // 1. FILTROS TEMPORALES (Año y Mes Actual)
        $añoActual = Carbon::now()->year;
        $mesActual = Carbon::now()->month;

        // 2. TARJETAS DE CARD KPI (Uso de agregadores de Eloquent)
        $comprasMesQuery = Compra::whereYear('fecha_pedido', $añoActual)
            ->whereMonth('fecha_pedido', $mesActual)
            ->where('estado', '!=', 'anulada');

        $kpis = (object) [
            'total_invertido' => (float) $comprasMesQuery->clone()->where('estado', 'recibida')->sum('total'),
            'total_ordenes' => $comprasMesQuery->clone()->count(),
            'ordenes_pendientes' => $comprasMesQuery->clone()->where('estado', 'pendiente')->count(),
        ];

        // Proveedor Top usando Eloquent con su relación 'proveedor'
        $proveedorTopRaw = Compra::whereYear('fecha_pedido', $añoActual)
            ->whereMonth('fecha_pedido', $mesActual)
            ->where('estado', 'recibida')
            ->select('proveedor_id', DB::raw('SUM(total) as total_proveedor'))
            ->groupBy('proveedor_id')
            ->orderByDesc('total_proveedor')
            ->with('proveedor') // Carga ansiosa la relación Proveedor
            ->first();

        $proveedorTop = (object) [
            'razon_social' => $proveedorTopRaw->proveedor->razon_social ?? 'Ninguno',
            'total_proveedor' => $proveedorTopRaw->total_proveedor ?? 0
        ];

        // 3. GRÁFICO ANUAL (Evolución por meses con Eloquent)
        $comprasMensualesRaw = Compra::whereYear('fecha_pedido', $añoActual)
            ->where('estado', 'recibida')
            ->select(DB::raw('MONTH(fecha_pedido) as mes'), DB::raw('SUM(total) as total'))
            ->groupBy('mes')
            ->pluck('total', 'mes')
            ->toArray();

        $comprasMensualesAnual = [];
        for ($i = 1; $i <= 12; $i++) {
            $comprasMensualesAnual[] = (float) ($comprasMensualesRaw[$i] ?? 0);
        }

        // 4. GRÁFICO DE DONA (Inversión por Categoría vía DetalleCompra con relaciones)
        $detallesMes = DetalleCompra::whereHas('compra', function ($query) use ($añoActual, $mesActual) {
            $query->whereYear('fecha_pedido', $añoActual)
                ->whereMonth('fecha_pedido', $mesActual)
                ->where('estado', 'recibida');
        })
            ->with('producto.categoria') // Cruza automáticamente a través de Producto hasta Categoría
            ->get();

        // Agrupamos la colección resultante usando colecciones de Laravel (Sin querys SQL complejas)
        $categoriasDona = $detallesMes->groupBy(function ($detalle) {
            return $detalle->producto->categoria->nombre ?? 'Sin Categoría';
        })->map(function ($grupo, $nombreCategoria) {
            return (object) [
                'categoria' => $nombreCategoria,
                'total_categoria' => $grupo->sum('total')
            ];
        })->values();

        // 5. VARIACIÓN DE PRECIOS (Única por Producto - Compara la última factura contra la anterior)
        $añoActual = Carbon::now()->year;
        $mesActual = Carbon::now()->month;

        $detallesMes = DetalleCompra::whereHas('compra', function ($query) use ($añoActual, $mesActual) {
            $query->whereYear('fecha_pedido', $añoActual)
                ->whereMonth('fecha_pedido', $mesActual)
                ->where('estado', 'recibida');
        })
            ->with(['producto', 'compra.proveedor'])
            ->orderBy('id', 'desc') // Ordenamos de más reciente a más antiguo primero
            ->get();

        $alertasPrecios = $detallesMes
            // --- CLAVE: Agrupamos por producto para procesar la última compra de cada uno ---
            ->groupBy('producto_id')
            ->map(function ($grupoProductos) {
                // Al estar ordenado desc, el primero del grupo es la compra MÁS RECIENTE del mes
                $ultimoDetalle = $grupoProductos->first();

                // Buscamos la compra histórica ANTERIOR a esta última (fuera de este registro)
                $compraAnterior = DetalleCompra::where('producto_id', $ultimoDetalle->producto_id)
                    ->where('id', '<', $ultimoDetalle->id)
                    ->whereHas('compra', function ($query) {
                        $query->where('estado', 'recibida');
                    })
                    ->orderBy('id', 'desc')
                    ->first();

                // Si no hay historial hacia atrás, usamos su precio base como pivote
                $precioAnterior = $compraAnterior
                    ? $compraAnterior->precio_unitario_compra
                    : $ultimoDetalle->producto->precio_compra;

                $diferencia = $ultimoDetalle->precio_unitario_compra - $precioAnterior;

                return (object) [
                    'producto' => $ultimoDetalle->producto->nombre,
                    'proveedor' => $ultimoDetalle->compra->proveedor->razon_social,
                    'precio_catalogo' => $precioAnterior, // Actúa como el costo previo
                    'precio_facturado' => $ultimoDetalle->precio_unitario_compra,
                    'diferencia' => $diferencia,
                    'tipo' => $diferencia > 0 ? 'subio' : ($diferencia < 0 ? 'bajo' : 'igual'),
                    'numero_comprobante' => $ultimoDetalle->compra->numero_comprobante
                ];
            })
            ->filter(function ($item) {
                return $item->tipo !== 'igual'; // Descartamos si no varió
            })
            ->sortByDesc(function ($item) {
                return abs($item->diferencia); // Ordenamos por mayor impacto económico
            })
            ->take(5) // Top 5 de licores con variaciones
            ->values();

        return view('modules.reports.compras', compact(
            'kpis',
            'proveedorTop',
            'comprasMensualesAnual',
            'categoriasDona',
            'alertasPrecios',
            'titulo'
        ));
    }
}
