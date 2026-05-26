<?php

namespace App\Http\Controllers\Reports;

use App\Http\Controllers\Controller;
use App\Models\MovimientoStock;
use App\Models\Venta;
use Illuminate\Http\Request;

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
}
