<?php

namespace App\Http\Controllers\Reports;

use App\Http\Controllers\Controller;
use App\Models\Compra;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CompraReportController extends Controller
{
    public function getGastosMensuales()
    {
        $gastos = Compra::select(
            DB::raw('MONTH(fecha_compra) as mes'),
            DB::raw('SUM(total) as total')
        )
            ->whereYear('fecha_compra', Carbon::now()->year)
            ->where('estado', 'recibido') // Solo lo que ya se pagó/ingresó
            ->groupBy('mes')
            ->orderBy('mes')
            ->pluck('total', 'mes')
            ->toArray();

        // Rellenamos los meses que no tengan compras con 0
        $data = [];
        for ($i = 1; $i <= 12; $i++) {
            $data[] = $gastos[$i] ?? 0;
        }

        return response()->json($data);
    }

    public function getGastosPorCategoria()
    {
        $categorias = DB::table('detalle_compras')
            ->join('compras', 'detalle_compras.compra_id', '=', 'compras.id')
            ->join('productos', 'detalle_compras.producto_id', '=', 'productos.id')
            ->join('categorias', 'productos.categoria_id', '=', 'categorias.id')
            ->select('categorias.nombre as categoria', DB::raw('SUM(detalle_compras.subtotal) as total'))
            ->where('compras.estado', 'recibido')
            ->whereMonth('compras.fecha_compra', Carbon::now()->month) // Mes actual
            ->groupBy('categorias.nombre')
            ->get();

        return response()->json($categorias);
    }
}
