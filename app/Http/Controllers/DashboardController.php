<?php

namespace App\Http\Controllers;

use App\Models\DetalleVenta;
use App\Models\MovimientoStock;
use App\Models\Producto;
use App\Models\Venta;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $titulo = 'Principal';
        $ventasHoy = Venta::whereDate('fecha_venta', Carbon::today())
            ->where('estado', 'completada')
            ->sum('total');

        $ventasMes = Venta::whereMonth('fecha_venta', Carbon::now()->month)
            ->whereYear(
                'fecha_venta',
                Carbon::now()->year
            )
            ->where('estado', 'completada')
            ->sum('total');

        // $numerodeventashoy = Venta::where('estado', 'completada')->count();
        $productosVendidosHoy = DetalleVenta::whereHas(
            'venta',
            function ($query) {
                $query->whereDate(
                    'fecha_venta',
                    Carbon::today()
                )
                    ->where('estado', 'completada');
            }
        )
            ->sum('cantidad');

        $productosBajoStock = Producto::whereColumn('stock','<=','stock_minimo')->count();

        /*
|--------------------------------------------------------------------------
| VENTAS ULTIMOS 7 DIAS
|--------------------------------------------------------------------------
*/

        $ventasUltimos7Dias = Venta::select(
            DB::raw('DATE(fecha_venta) as fecha'),
            DB::raw('SUM(total) as total')
        )
            ->where('estado', 'completada')
            ->whereDate(
                'fecha_venta',
                '>=',
                Carbon::now()->subDays(6)
            )
            ->groupBy('fecha')
            ->orderBy('fecha')
            ->get();

        $fechas = [];
        $totales = [];

        foreach ($ventasUltimos7Dias as $venta) {

            $fechas[] = Carbon::parse(
                $venta->fecha
            )->format('d/m');

            $totales[] = $venta->total;
        }

        /*
|--------------------------------------------------------------------------
| PRODUCTOS MAS VENDIDOS
|--------------------------------------------------------------------------
*/
        $productosMasVendidos = DetalleVenta::select(
            'producto_id',
            DB::raw('SUM(cantidad) as total_vendidos')
        )
            ->with('producto')
            ->groupBy('producto_id')
            ->orderByDesc('total_vendidos')
            ->take(5)
            ->get();

        /*
|--------------------------------------------------------------------------
| ULTIMOS MOVIMIENTOS STOCK
|--------------------------------------------------------------------------
*/

        $ultimosMovimientos = MovimientoStock::with(
            'producto'
        )
            ->latest()
            ->take(5)
            ->get();

        return view(
            "modules.dashboard.index",
            compact(
                'titulo',
                'ventasHoy',
                'ventasMes',
                'productosVendidosHoy',
                'productosBajoStock',
                'fechas',
                'totales',
                'productosMasVendidos',
                'ultimosMovimientos'
            )
        );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
